<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmMutationTargetRequest;
use App\Repositories\MutationItemRequestRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMutationItemRequest;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use App\Models\MutationItemRequest;
use Illuminate\Support\Facades\Auth;
use Throwable;

class MutationItemRequestController extends Controller
{
    protected $mutationItemRequestRepository;
    protected $itemRepository;
    protected $userRepository;

    public function __construct(MutationItemRequestRepositoryInterface $mutationItemRequestRepository, ItemRepositoryInterface $itemRepository, UserRepositoryInterface $userRepository)
    {
        $this->mutationItemRequestRepository = $mutationItemRequestRepository;
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 5);
        $mutationItemRequests = $this->mutationItemRequestRepository->all($perPage);
        $users = null;
        if ($user->role === 'ADMIN') {
            $users = User::where('role', 'UNIT')->orderBy('name')->get();
        }
        return view('mutation_item_requests.index', compact('mutationItemRequests', 'users'));
    }

    public function create()
    {
        $items = $this->itemRepository->all();
        $users = $this->userRepository->all();
        return view('mutation_item_requests.create', compact('items', 'users'));
    }

    public function store(StoreMutationItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->mutationItemRequestRepository->create($validated);
            return redirect()->route('items.index')->with('success', 'Permintaan mutasi berhasil dibuat.');
        } catch (QueryException $e) {
            $code = $e->getCode();
            $msg = $code == 23000 ? 'Data duplikat atau referensi tidak valid.' : 'Kesalahan database. Coba lagi.';
            return redirect()->back()->withInput()->with('error', $msg);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create mutation item request.');
        }
    }

    public function confirm(ConfirmMutationTargetRequest $request, MutationItemRequest $mutationItemRequest)
    {
        $data = $request->validated();

        $user = Auth::user();
        $userId = $user->id;
        $field = $data['target'] === 'unit' ? 'unit_confirmed' : 'recipient_confirmed';

        if ($data['target'] === 'unit' && $userId !== $mutationItemRequest->from_user_id) {
            return back()->with('error', 'Hanya unit asal yang dapat mengkonfirmasi.');
        }

        if ($data['target'] === 'recipient' && $userId !== $mutationItemRequest->to_user_id) {
            return back()->with('error', 'Hanya unit tujuan yang dapat mengkonfirmasi.');
        }

        if ($mutationItemRequest->{$field}) {
            return back()->with('success', 'Status sudah dikonfirmasi.');
        }

        try {
            $this->mutationItemRequestRepository->confirm($mutationItemRequest->id, $field);
            return back()->with('success', 'Konfirmasi berhasil.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat konfirmasi.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal mengkonfirmasi.');
        }
    }
}
