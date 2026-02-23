<?php

namespace App\Http\Controllers\Backend\SupportTicket;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketDetail;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Image;
use Auth;
use DB;

class SupportTicketController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('ticket-support-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getOwnerId();
        if (Auth::user()->role == 'superadmin') {
            $supportTicketData = SupportTicket::orderBy('id', 'desc')->paginate(10);
            $allSupportTicketCount = SupportTicket::orderBy('id', 'desc')->count();
        }else{
            $supportTicketData = SupportTicket::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
            $allSupportTicketCount = SupportTicket::orderBy('id', 'desc')->where('user_id', $userId)->count();
        }
        
        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Support ticket fetched successfully.',
                'status_code' => 200,
                'supportTicketData' => $supportTicketData,
                'allSupportTicketCount' => $allSupportTicketCount,
            ], 200);
        }

        return view('backend.supportModule.index',compact('supportTicketData','allSupportTicketCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.supportModule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('ticket-support-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'support_type' => 'required',
            'subject' => 'required',
            'details' => 'required',
            'image' => 'nullable|mimes:pdf,docx,xml,jpg,jpeg,png,gif,svg,webp',
        ]);

        // Fetch user ID
        $userId = CurrentUser::getOwnerId();
        $userIdFCU = CurrentUser::getUserIdFCU();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check logo image...
        foreach (['image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/supportFile');
            }
        }

        DB::beginTransaction();
        try {
            $lastTicket = SupportTicket::orderBy('id', 'desc')->first();
            $nextTicketNumber = $lastTicket ? (intval(substr($lastTicket->ticket_number, 4)) + 1) : 10001;
            $data['ticket_number'] = 'TKT-' . str_pad($nextTicketNumber, 5, '0', STR_PAD_LEFT);
            $data['ticket_by_id'] = $userIdFCU;
            $supportTicket = SupportTicket::create($data);

            SupportTicketDetail::create([
                'ticket_by_id' => $userIdFCU,
                'support_ticket_id' => $supportTicket->id,
                'subject' => $data['subject'],
                'details' => $data['details'],
                'image' => $data['image'] ?? null,
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Support ticket created successfully.',
                    'status_code' => 200,
                    'ticketData' => $data
                ], 200);
            }

            Toastr::success('Support ticket created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('ticket-support.index');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, something went wrong.',
                    'status_code' => 500,
                    'error' => $e->getMessage()
                ], 500);
            }

            Toastr::error('Sorry, something went wrong!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To file upload...
    private function uploadFile($file, $path)
    {
        $fileName = now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = "$path/$fileName";
        Storage::disk('public')->put($filePath, file_get_contents($file));
        return $fileName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('ticket-support-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'room_no'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleRoomData = SupportTicket::where('id', $id)->first();

        if($singleRoomData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Room updated successfully.',
                    'status_code' => 200,
                    'singleRoomData' => $singleRoomData
                ], 200);
            }

            Toastr::success('Room updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('ticket-support-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleRoomData = SupportTicket::where('id', $id)->first();
        if($singleRoomData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Room deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Room deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To shown support ticket details page...
    public function supportTicketDetail(Request $request, $id)
    {
        if (!auth()->user()->can('ticket-support-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }
            return redirect()->route('error.accessDenied');
        }

        $supportTicketDetails = SupportTicket::with(['supportTicketDetailData' => function ($query) {
                                    $query->orderBy('id', 'desc');
                                }])->where('id', $id)->firstOrFail();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Support ticket details retrieved successfully.',
                'status_code' => 200,
                'supportTicketDetails' => $supportTicketDetails,
            ], 200);
        }

        return view('backend.supportModule.supportTicketDetails', compact('supportTicketDetails'));
    }
    
    //To shown support ticket close page...
    public function supportTicketClose(Request $request, $id)
    {
        if (!auth()->user()->can('ticket-support-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }
            return redirect()->route('error.accessDenied');
        }

        SupportTicket::where('id', $id)->update(['status'=>2]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Support ticket closed successfully.',
                'status_code' => 200,
            ], 200);
        }

        Toastr::success('Support ticket closed successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    public function supportTicketRepply(Request $request, $id)
    {
        // Check user permission
        if (!auth()->user()->can('ticket-support-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }
            return redirect()->route('error.accessDenied');
        }

        // Validate request
        $request->validate([
            'details' => 'required|string',
        ]);

        // Fetch user ID
        $userId = CurrentUser::getUserId();
        $userIdFCU = CurrentUser::getUserIdFCU();
        $supportTicket = SupportTicket::findOrFail($id);
        SupportTicketDetail::create([
            'ticket_reply_id' => $userIdFCU,
            'support_ticket_id' => $supportTicket->id,
            'subject' => $$supportTicket->subject ?? 'Reply to Ticket #' . $supportTicket->ticket_number,
            'details' => $request->details,
            'image' => null,
        ]);
        

        // Create a new SupportTicketDetail record for the reply
        $supportTicket->update([
            'status' => 1,
        ]);

        // Success response
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Reply added successfully.',
                'status_code' => 200,
            ], 200);
        }

        Toastr::success('Reply added successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
