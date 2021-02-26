<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lend;
use App\Models\User;


class LendController extends Controller
{
    public function browse (Request $req) 
    {
        $this->init();

        $params = [
            'title' => 'Lend', 
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
        ];
        
        return view('backend.lend.index', $params);
    }


    public function datatables (Request $req) 
    {
        $this->init();
        $filter = [['l.deleted_at', '=', null], ['l.data_owner', '=', auth()->user()->id], ];
        $data = [];

        $dtColumns = $req->columns;
        $dtStart = $req->start;
        $dtLength = $req->length;
        $dtSearch = $req->search;
        $dtOrder = $req->order;
        $dtDraw = $req->draw;

        $orderColumn = $dtColumns[$dtOrder[0]['column']]['data'];
        $orderDir = $dtOrder[0]['dir'];

        $rawFilter = '';
        

        // $tmp = Lend::where($filter)->orderBy($orderColumn, $orderDir)->paginate($dtLength);
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


    public function add () 
    {
        $params = [
            'title' => 'Lend',
            'users' => User::where([['id', '!=', auth()->user()->id]])->orderBy('name', 'ASC'),
        ];
        return view('backend.lend.add', $params);
    }


    public function processAdd (Request $req) 
    {
        $this->validate($req, [
            'is_member' => 'required|numeric|min:0',
            'name' => 'nullable|max:250',
            'user' => 'nullable|numeric|min:1',
            'status' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'lend_file' => 'required|image|mimes:jpg,png,jpeg,gif',
            'description' => 'required',
        ]);

        $this->init();

        $path = $this->baseUploadPath.$this->subUploadPath;
        if (! \File::isDirectory($path)) {
            \File::makeDirectory($path);
        }

        $file = $req->file('lend_file');
        $newFilename = time().'.'.$file->getClientOriginalExtension();
        $file->move($path, $newFilename);

        Lend::create([
            'name' => $req->name,
            'is_member' => $req->is_member,
            'user' => $req->user ? $req->user : 0,
            'status' => $req->status,
            'nominal' => $req->nominal,
            'lend_file' => $this->subUploadPath.$newFilename,
            'description' => $req->description,
            'data_owner' => auth()->user()->id,
        ]);

        return redirect()->route('lend')->with('message', 'Data saved');
    }


    public function read (Lend $lend) 
    {
        $this->init();

        $params = [
            'title' => 'Lend',
            'users' => User::where([['id', '!=', auth()->user()->id]])->orderBy('name', 'ASC'),
            'data' => Lend::where([['id', '=', $lend->id]])->first(),
        ];

        return view('backend.lend.edit', $params);
    }


    public function edit (Request $req, Lend $lend) 
    {
        $this->validate($req, [
            'is_member' => 'required|numeric|min:0',
            'name' => 'nullable|max:250',
            'user' => 'nullable|numeric|min:1',
            'status' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'lend_file' => 'required|image|mimes:jpg,png,jpeg,gif',
            'description' => 'required',
        ]);

        $this->init();

        $path = $this->baseUploadPath.$this->subUploadPath;
        if (! \File::isDirectory($path)) {
            \File::makeDirectory($path);
        }

        $file = $req->file('lend_file');
        $newFilename = time().'.'.$file->getClientOriginalExtension();
        // \Image::make($file)->save($path.'/'.$newFilename);
        if (\File::delete($this->baseUploadPath.$lend->lend_file)) {
            $file->move($path, $newFilename);
        }
        
        $lend->name = $req->name;
        $lend->is_member = $req->is_member;
        $lend->user = $req->user ? $req->user : '0';
        $lend->status = $req->status;
        $lend->nominal = $req->nominal;
        $lend->lend_file = $this->subUploadPath.$newFilename;
        $lend->description = $req->description;
        $lend->save();

        return redirect()->route('lend')->with('message', 'Data saved');
    }


    public function delete (Lend $lend) 
    {
        $this->init();

        // \File::delete($this->baseUploadPath.$lend->lend_file);
        $lend->delete();

        return [
            'csrfTokenName' => $this->csrfTokenName,
            'csrfTokenValue' => $this->csrfTokenValue,
            'type' => 'success', 
            'detail' => ['Data deleted']
        ];
    }
}
