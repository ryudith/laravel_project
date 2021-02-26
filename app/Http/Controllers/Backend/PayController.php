<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lend;
use App\Models\Pay;


class PayController extends Controller
{
    public function lendBrowse () 
    {
        $this->init();

        $params = [
            'title' => 'Pay Lend', 
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
        ];
        
        return view('backend.pay.index_lend', $params);
    }


    public function lendDatatables (Request $req) 
    {
        $this->init();
        $filter = [['l.deleted_at', '=', null]];
        $data = [];

        $dtColumns = $req->columns;
        $dtStart = $req->start;
        $dtLength = $req->length;
        $dtSearch = $req->search;
        $dtOrder = $req->order;
        $dtDraw = $req->draw;

        $orderColumn = $dtColumns[$dtOrder[0]['column']]['data'];
        $orderDir = $dtOrder[0]['dir'];


        $resultset = \DB::table('lends AS l')->orderBy($orderColumn, $orderDir)
            ->select('l.id', 'l.created_at', 'l.nominal', 'l.name', 'l.status', 'l.is_member', 'u.name AS user_name')
            ->where($filter)
            ->leftJoin('users AS u', 'u.id', '=', 'l.user')
            ->limit($dtLength)
            ->offset($dtStart);
        
        if (isset($dtSearch['value']) && $dtSearch['value']) {
            $search = strtolower($dtSearch['value']);
            $rawFilter = "LOWER(l.name) LIKE '%".$search."%' OR LOWER(u.name) LIKE '%".$search."%'";

            $resultset->whereRaw($rawFilter);
        }
        
        $tmp = $resultset->get();
        
        $recordsTotal = \DB::table('lends AS l')->count();
        $recordsFiltered = \DB::table('lends AS l')->where($filter)
            ->leftJoin('users AS u', 'u.id', '=', 'l.user')
            ->count();

        foreach ($tmp as $i => $item) {
            $newItem = [
                'id' => $item->id,
                'created_at' => $item->created_at,
                'nominal' => $item->nominal,
            ];

            if ($item->is_member > 0) {
                $newItem['name'] = $item->user_name;
            } else {
                $newItem['name'] = $item->name;
            }

            if ($item->status == '0') {
                $newItem['status'] = 'Disable';
            } else if ($item->status == '2') {
                $newItem['status'] = 'Paid';
            } else {
                $newItem['status'] = 'Unpaid';
            }

            $data[$i] = $newItem;
        }

        return [
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
            'draw' => $req->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
    }


    public function browse (Request $req, Lend $lend) 
    {
        $this->init();

        $totalPayment = Pay::where([ ['status', '=', 1], ['lend_id', '=', $lend->id] ])->sum('nominal');

        $params = [
            'title' => 'Pay Lend', 
            'lend' => $lend,
            'totalPayment' => $totalPayment,
            'leftPayment' => $lend->nominal - $totalPayment,
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
        ];
        
        return view('backend.pay.index', $params);
    }


    public function datatables (Request $req, Lend $lend) 
    {
        $this->init();
        $filter = [['pays.deleted_at', '=', null], ['l.id', '=', $lend->id], ['pays.data_owner', '=', auth()->user()->id], ];
        $data = [];

        $dtColumns = $req->columns;
        $dtStart = $req->start;
        $dtLength = $req->length;
        $dtSearch = $req->search;
        $dtOrder = $req->order;
        $dtDraw = $req->draw;

        $orderColumn = $dtColumns[$dtOrder[0]['column']]['data'];
        $orderDir = $dtOrder[0]['dir'];


        $recordsTotal = Pay::with(['users', 'lends'])->leftJoin('users AS u', 'u.id', '=', 'pays.user')
            ->leftJoin('lends AS l', 'l.id', '=', 'pays.lend_id');
        $recordsFiltered = Pay::with(['users', 'lends'])->where($filter)
            ->leftJoin('users AS u', 'u.id', '=', 'pays.user')
            ->leftJoin('lends AS l', 'l.id', '=', 'pays.lend_id');
        $resultset = Pay::where($filter)
            ->select('pays.id', 'pays.created_at', 'pays.nominal', 'pays.status', 'pays.note')
            ->orderBy($orderColumn, $orderDir)
            ->limit($dtLength)
            ->offset($dtStart)
            ->leftJoin('users AS u', 'u.id', '=', 'pays.user')
            ->leftJoin('lends AS l', 'l.id', '=', 'pays.lend_id');

        
        if (isset($dtSearch['value']) && $dtSearch['value']) {
            $search = strtolower($dtSearch['value']);
            $rawFilter = "LOWER(pays.note) LIKE '%".$search."%'";

            $resultset->whereRaw($rawFilter);
            $recordsFiltered->whereRaw($rawFilter);
        }
        
        $tmp = $resultset->get();

        foreach ($tmp as $i => $item) {
            $newItem = [
                'id' => $item->id,
                'created_at' => $item->created_at->toDateTimeString(),
                'nominal' => $item->nominal,
                'note' => $item->note,
            ];

            if ($item->status == '0') {
                $newItem['status'] = 'Cancel';
            } else {
                $newItem['status'] = 'Accept';
            }

            $data[$i] = $newItem;
        }

        return [
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
            'draw' => $req->draw,
            'recordsTotal' => $recordsTotal->count(),
            'recordsFiltered' => $recordsFiltered->count(),
            'data' => $data,
        ];
    }


    public function add (Request $req, Lend $lend) 
    {
        $this->init();

        $totalPayment = Pay::where([ ['status', '=', 1], ['lend_id', '=', $lend->id], ])->sum('nominal');

        $params = [
            'title' => 'Pay Lend', 
            'lend' => $lend,
            'leftLending' => $lend->nominal - $totalPayment,
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
        ];
        
        return view('backend.pay.add', $params);
    }


    public function processAdd (Request $req, Lend $lend) 
    {
        $totalPayment = Pay::where([ ['status', '=', 1], ['lend_id', '=', $lend->id], ])->sum('nominal');
        $lefLending = $lend->nominal - $totalPayment;

        $this->validate($req, [
            'status' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|max:'.$lefLending,
            'lend_file' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'note' => 'nullable',
        ]);

        $this->init();

        $path = $this->baseUploadPath.$this->subUploadPayPath;
        if (! \File::isDirectory($path)) {
            \File::makeDirectory($path);
        }

        $newFilename = '';
        $file = $req->file('pay_file');
        if ($file) {
            $newFilename = time().'.'.$file->getClientOriginalExtension();
            $file->move($path, $newFilename);

            \Image::make($path.'/'.$newFilename)->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/'.$newFilename);
        }
        
        Pay::create([
            'user' => $lend->user > 0 ? $lend->user : null,
            'lend_id' => $lend->id,
            'status' => $req->status,
            'nominal' => $req->nominal,
            'pay_file' => $newFilename ? $this->subUploadPayPath.$newFilename : $newFilename,
            'note' => $req->note ? $req->note : '',
            'data_owner' => auth()->user()->id,
        ]);

        return redirect()->route('pay', $lend->id)->with('message', 'Data saved');
    }


    public function read (Request $req, Lend $lend, Pay $pay) 
    {
        $this->init();

        $totalPayment = Pay::where([ ['status', '=', 1], ['lend_id', '=', $lend->id], ])->sum('nominal');

        $params = [
            'title' => 'Edit Pay',
            'lend' => $lend,
            'data' => $pay,
            'leftLending' => $lend->nominal - $totalPayment,
        ];

        return view('backend.pay.edit', $params);
    }


    public function edit (Request $req, Lend $lend, Pay $pay) 
    {
        $totalPayment = Pay::where([ ['status', '=', 1], ['lend_id', '=', $lend->id], ])->sum('nominal');
        $lefLending = $lend->nominal - $totalPayment;

        $this->validate($req, [
            'status' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|max:'.$lefLending,
            'pay_file' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'note' => 'nullable',
        ]);

        $this->init();

        $path = $this->baseUploadPath.$this->subUploadPayPath;
        if (! \File::isDirectory($path)) {
            \File::makeDirectory($path);
        }

        $newFilename = '';
        $file = $req->file('pay_file');
        if ($file) {
            $newFilename = time().'.'.$file->getClientOriginalExtension();

            if ($pay->pay_file) {
                \File::delete($this->baseUploadPath.$pay->pay_file);
            }

            $file->move($path, $newFilename);

            \Image::make($path.'/'.$newFilename)->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/'.$newFilename);
        }
        
        $pay->status = $req->status;
        $pay->nominal = $req->nominal;
        $pay->pay_file = $newFilename ? $this->subUploadPayPath.$newFilename : $newFilename;
        $pay->note = $req->note ? $req->note : '';
        $pay->save();

        return redirect()->route('pay', $lend->id)->with('message', 'Data saved');
    }


    public function delete (Request $req, Pay $pay) 
    {
        $this->init();

        // \File::delete($this->baseUploadPayPath.$lend->pay_file);
        $pay->delete();

        return [
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
            'type' => 'success', 
            'detail' => ['Data deleted']
        ];
    }
}
