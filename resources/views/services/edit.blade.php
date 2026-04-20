@extends('layouts.app')

@section('page-title', 'Edit Service')
@section('page-sub', 'Update the details of ' . $service->name)

@section('content')
<style>
.form-wrap{max-width:720px;margin:0 auto;font-family:'Outfit',sans-serif;display:flex;flex-direction:column;gap:20px}
.form-header{display:flex;align-items:center;justify-content:space-between}
.form-title{font-size:22px;font-weight:900;color:#111827;margin:0}
.form-sub{font-size:13px;color:#9ca3af;margin:3px 0 0}
.back-btn{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#6b7280;text-decoration:none;padding:8px 16px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;transition:all .2s}
.back-btn:hover{border-color:#111827;color:#111827}
.back-btn svg{width:15px;height:15px}
.form-card{background:#fff;border-radius:20px;border:1px solid #e5e7eb;box-shadow:0 1px 6px rgba(0,0,0,.05);overflow:hidden}
.form-card-header{padding:18px 22px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:12px}
.card-icon{width:38px;height:38px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.card-icon.yellow{background:#fef9c3}
.card-icon svg{width:18px;height:18px;color:#ca8a04}
.card-title{font-size:14px;font-weight:800;color:#111827}
.card-sub{font-size:11px;color:#9ca3af;margin-top:1px}
.form-card-body{padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:18px}
.field-full{grid-column:1/-1}
.field label{display:block;font-size:11px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px}
.field input,.field select{width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;font-family:'Outfit',sans-serif;color:#111827;outline:none;transition:all .2s}
.field input:focus,.field select:focus{border-color:#F7DF79;background:#fff;box-shadow:0 0 0 3px rgba(247,223,121,.2)}
.field .error{font-size:11px;color:#ef4444;font-weight:600;margin-top:4px}
.field-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px}
.field-row label{margin-bottom:0}
.add-cat-btn{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:900;color:#ca8a04;background:none;border:none;cursor:pointer;text-transform:uppercase;letter-spacing:.06em;font-family:'Outfit',sans-serif}
.add-cat-btn svg{width:12px;height:12px}
.toggle-wrap{display:flex;align-items:center;gap:12px;padding:14px;background:#f9fafb;border-radius:12px;border:1.5px solid #e5e7eb;cursor:pointer;transition:border-color .2s}
.toggle-wrap:hover{border-color:#F7DF79}
.toggle{position:relative;width:44px;height:24px;flex-shrink:0}
.toggle input{opacity:0;width:0;height:0;position:absolute}
.toggle-track{position:absolute;inset:0;background:#e5e7eb;border-radius:24px;transition:background .2s}
.toggle input:checked~.toggle-track{background:#F7DF79}
.toggle-thumb{position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.2);transition:transform .2s}
.toggle input:checked~.toggle-thumb{transform:translateX(20px)}
.toggle-label{font-size:13px;font-weight:700;color:#374151}
.toggle-sub{font-size:11px;color:#9ca3af;margin-top:1px}
.form-footer{padding:18px 22px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:flex-end;gap:10px;background:#fafafa}
.btn-discard{padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;font-size:13px;font-weight:700;color:#6b7280;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .2s;text-decoration:none}
.btn-discard:hover{border-color:#9ca3af;color:#111827}
.btn-submit{display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:10px;border:none;background:#F7DF79;font-size:13px;font-weight:800;color:#111827;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .2s;box-shadow:0 4px 12px rgba(247,223,121,.4)}
.btn-submit:hover{background:#fde047;transform:translateY(-1px)}
.btn-submit svg{width:16px;height:16px}
/* Delete zone */
.delete-zone{background:#fff;border-radius:20px;border:1.5px dashed #fecaca;overflow:hidden}
.delete-zone-body{padding:20px 22px;display:flex;align-items:center;justify-content:space-between;gap:16px}
.delete-zone-title{font-size:13px;font-weight:800;color:#dc2626;margin-bottom:3px}
.delete-zone-desc{font-size:12px;color:#9ca3af}
.btn-delete{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;border:1.5px solid #fecaca;background:#fef2f2;font-size:12px;font-weight:700;color:#dc2626;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .2s;white-space:nowrap}
.btn-delete:hover{background:#dc2626;color:#fff;border-color:#dc2626}
.btn-delete svg{width:14px;height:14px}
/* Modal */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.4);backdrop-filter:blur(4px);z-index:100;display:flex;align-items:center;justify-content:center;padding:20px}
.modal-card{background:#fff;border-radius:20px;width:100%;max-width:420px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.15)}
.modal-head{padding:18px 22px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between}
.modal-head-title{font-size:16px;font-weight:900;color:#111827}
.modal-close{background:none;border:none;cursor:pointer;color:#9ca3af;padding:4px;border-radius:8px;transition:all .2s;display:flex}
.modal-close:hover{background:#f3f4f6;color:#111827}
.modal-close svg{width:18px;height:18px}
.modal-body{padding:22px}
.modal-foot{padding:14px 22px;border-top:1px solid #f3f4f6;display:flex;gap:10px;background:#fafafa}
.btn-cancel{flex:1;padding:10px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;font-size:13px;font-weight:700;color:#6b7280;cursor:pointer;font-family:'Outfit',sans-serif}
.btn-save{flex:1;padding:10px;border-radius:10px;border:none;background:#F7DF79;font-size:13px;font-weight:800;color:#111827;cursor:pointer;font-family:'Outfit',sans-serif}
.btn-save:hover{background:#fde047}
</style>

<div class="form-wrap">

    {{-- Header --}}
    <div class="form-header">
        <div>
            <h1 class="form-title">Edit Service</h1>
            <p class="form-sub">Updating <strong>{{ $service->name }}</strong></p>
        </div>
        <a href="{{ route('services.index') }}" class="back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Back
        </a>
    </div>

    @if($errors->any())
    <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:12px;padding:14px 18px">
        <p style="font-size:12px;font-weight:800;color:#dc2626;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Please fix the following:</p>
        @foreach($errors->all() as $error)
        <p style="font-size:13px;color:#ef4444;font-weight:600">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Form Card --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="card-icon yellow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m6 3 6 6 6-6"/><path d="M20 21H4"/><path d="m6 21 6-6 6 6"/></svg>
            </div>
            <div>
                <div class="card-title">Service Details</div>
                <div class="card-sub">Update the information for this service</div>
            </div>
        </div>

        <form action="{{ route('services.update', $service) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-card-body">

                <div class="field field-full">
                    <label>Service Name</label>
                    <input type="text" name="name" placeholder="e.g. Signature Haircut" required value="{{ old('name', $service->name) }}">
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <div class="field-row">
                        <label>Category</label>
                        <button type="button" onclick="showCategoryModal()" class="add-cat-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            New
                        </button>
                    </div>
                    <select name="category_id" id="category_id" required>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $service->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label>Base Price (PKR)</label>
                    <input type="number" name="price" placeholder="0" required step="0.01" min="0" value="{{ old('price', $service->price) }}">
                    @error('price')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label>Duration (Minutes)</label>
                    <input type="number" name="duration" placeholder="30" required min="1" value="{{ old('duration', $service->duration) }}">
                    @error('duration')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field field-full">
                    <label class="toggle-wrap" for="is_popular">
                        <div class="toggle">
                            <input type="checkbox" id="is_popular" name="is_popular" value="1" {{ old('is_popular', $service->is_popular) ? 'checked' : '' }}>
                            <div class="toggle-track"></div>
                            <div class="toggle-thumb"></div>
                        </div>
                        <div>
                            <div class="toggle-label">Mark as Popular</div>
                            <div class="toggle-sub">Popular services are highlighted in POS and booking views</div>
                        </div>
                    </label>
                </div>

            </div>

            <div class="form-footer">
                <a href="{{ route('services.index') }}" class="btn-discard">Discard</a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Zone --}}
    <div class="delete-zone">
        <div class="delete-zone-body">
            <div>
                <div class="delete-zone-title">Delete Service</div>
                <div class="delete-zone-desc">Once deleted, this service will no longer be available in POS or bookings.</div>
            </div>
            <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete {{ $service->name }} permanently?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    Delete Service
                </button>
            </form>
        </div>
    </div>

</div>

{{-- New Category Modal --}}
<div id="category-modal" class="modal-bg" style="display:none">
    <div class="modal-card">
        <div class="modal-head">
            <span class="modal-head-title">New Category</span>
            <button type="button" onclick="hideCategoryModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="field">
                <label>Category Name</label>
                <input type="text" id="new_category_name" placeholder="e.g. Skin Care" style="width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;font-family:'Outfit',sans-serif;outline:none;transition:all .2s">
            </div>
            <input type="hidden" id="category_type" value="service">
        </div>
        <div class="modal-foot">
            <button type="button" onclick="hideCategoryModal()" class="btn-cancel">Cancel</button>
            <button type="button" onclick="submitNewCategory()" class="btn-save">Save Category</button>
        </div>
    </div>
</div>

<script>
function showCategoryModal(){
    document.getElementById('category-modal').style.display='flex';
    setTimeout(()=>document.getElementById('new_category_name').focus(),100);
}
function hideCategoryModal(){
    document.getElementById('category-modal').style.display='none';
    document.getElementById('new_category_name').value='';
}
async function submitNewCategory(){
    const name=document.getElementById('new_category_name').value.trim();
    if(!name){document.getElementById('new_category_name').style.borderColor='#ef4444';return;}
    try{
        const res=await fetch('{{ route("categories.store") }}',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
            body:JSON.stringify({name,type:'service'})
        });
        const data=await res.json();
        if(data.success){
            const sel=document.getElementById('category_id');
            const opt=new Option(data.category.name,data.category.id);
            sel.add(opt);sel.value=data.category.id;
            hideCategoryModal();
        } else {alert(data.message||'Error');}
    } catch(e){alert('Failed to create category');}
}
document.addEventListener('keydown',e=>{if(e.key==='Escape')hideCategoryModal();});
</script>
@endsection
