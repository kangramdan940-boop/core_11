<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @isset($method) @method($method) @endisset

    <div class="card mb-3">
        <div class="card-header">Unggah Faktur PDF</div>
        <div class="card-body">
            <input type="file" name="pdf_file" id="fakturPdfInput" accept="application/pdf" class="form-control">
            <div id="fakturParseStatus" class="form-text mt-2"></div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Relasi & Tanggal</div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-3"><label class="form-label">Invoice Number</label><input type="text" name="invoice_number" value="{{ old('invoice_number', $document->invoice_number) }}" class="form-control" required></div>

                <div class="col-md-3"><label class="form-label">Transaction Type</label><input type="text" name="transaction_type" value="{{ old('transaction_type', $document->transaction_type) }}" class="form-control" maxlength="100"></div>
                <div class="col-md-3"><label class="form-label">Date</label><input type="date" name="date" value="{{ old('date', optional($document->date)->format('Y-m-d')) }}" class="form-control"></div>

            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Issuer</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Company</label><input type="text" name="issuer_company" value="{{ old('issuer_company', $document->issuer_company) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Business Unit</label><input type="text" name="issuer_business_unit" value="{{ old('issuer_business_unit', $document->issuer_business_unit) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Website</label><input type="text" name="issuer_website" value="{{ old('issuer_website', $document->issuer_website) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Address</label><textarea name="issuer_address" class="form-control" rows="2">{{ old('issuer_address', $document->issuer_address) }}</textarea></div>
                <div class="col-md-3"><label class="form-label">Phone</label><input type="text" name="issuer_phone" value="{{ old('issuer_phone', $document->issuer_phone) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">NPWP</label><input type="text" name="issuer_npwp" value="{{ old('issuer_npwp', $document->issuer_npwp) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">NPWP Holder</label><input type="text" name="issuer_npwp_holder" value="{{ old('issuer_npwp_holder', $document->issuer_npwp_holder) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">NPWP Address</label><textarea name="issuer_npwp_address" class="form-control" rows="2">{{ old('issuer_npwp_address', $document->issuer_npwp_address) }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Penerima Kuasa & Mitra</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Authorized Name</label><input type="text" name="authorized_receiver_name" value="{{ old('authorized_receiver_name', $document->authorized_receiver_name) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Authorized NIK</label><input type="text" name="authorized_receiver_nik" value="{{ old('authorized_receiver_nik', $document->authorized_receiver_nik) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Mitra Name</label><input type="text" name="customer_name" value="{{ old('customer_name', $document->customer_name) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Membership Number</label><input type="text" name="membership_number" value="{{ old('membership_number', $document->membership_number) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Membership Tier</label><input type="text" name="membership_tier" value="{{ old('membership_tier', $document->membership_tier) }}" class="form-control"></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Layanan & Lokasi</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Service Name</label><input type="text" name="service_name" value="{{ old('service_name', $document->service_name) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Boutique Code Name</label><input type="text" name="boutique_code_name" value="{{ old('boutique_code_name', $document->boutique_code_name) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Boutique Location</label><textarea name="boutique_location" class="form-control" rows="2">{{ old('boutique_location', $document->boutique_location) }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Pembayaran & Totals</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Currency</label><input type="text" name="currency" value="{{ old('currency', $document->currency ?? 'IDR') }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Grand Total (IDR)</label><input type="number" name="grand_total_idr" value="{{ old('grand_total_idr', $document->grand_total_idr) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">DPP (IDR)</label><input type="number" name="dpp_idr" value="{{ old('dpp_idr', $document->dpp_idr) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">PPN Rate (%)</label><input type="number" name="ppn_rate" value="{{ old('ppn_rate', $document->ppn_rate) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">PPN (IDR)</label><input type="number" name="ppn_idr" value="{{ old('ppn_idr', $document->ppn_idr) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Payment Method</label><input type="text" name="payment_method" value="{{ old('payment_method', $document->payment_method) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Virtual Account</label><input type="text" name="virtual_account" value="{{ old('virtual_account', $document->virtual_account) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Payment No</label><input type="text" name="payment_no" value="{{ old('payment_no', $document->payment_no) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Created By</label><input type="text" name="created_by" value="{{ old('created_by', $document->created_by) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Print By</label><input type="text" name="print_by" value="{{ old('print_by', $document->print_by) }}" class="form-control"></div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Items</div>
        <div class="card-body">
            <table class="table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width:80px;">No</th>
                        <th>Deskripsi</th>
                        <th style="width:120px;">Qty</th>
                        <th style="width:160px;">Berat (kg)</th>
                        <th style="width:160px;">Gramasi</th>
                        <th style="width:160px;">Harga</th>
                        <th style="width:160px;">Total</th>
                        <th style="width:80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $rows = old('items', $document->items?->toArray() ?? []); @endphp
                    @foreach($rows as $idx => $it)
                        <tr>
                            <td><input type="number" name="items[{{ $idx }}][no]" value="{{ $it['no'] ?? '' }}" class="form-control"></td>
                            <td><input type="text" name="items[{{ $idx }}][description]" value="{{ $it['description'] ?? '' }}" class="form-control"></td>
                            <td><input type="number" name="items[{{ $idx }}][quantity_pcs]" value="{{ $it['quantity_pcs'] ?? '' }}" class="form-control"></td>
                            <td><input type="number" step="0.1" name="items[{{ $idx }}][weight_kg]" value="{{ $it['weight_kg'] ?? '' }}" class="form-control"></td>
                            @php
                                $desc = (string) ($it['description'] ?? '');
                                $gramasiVal = '';
                                if ($desc !== '') {
                                    if (preg_match('/@\\s*([\\d.,]+)\\s*(?:gr\.?|gram)\\b/i', $desc, $m)) {
                                        $gramasiVal = str_replace(',', '.', $m[1]);
                                    }
                                }
                                if ($gramasiVal === '') {
                                    $q = (int) ($it['quantity_pcs'] ?? 0);
                                    $wkg = (float) ($it['weight_kg'] ?? 0);
                                    if ($q > 0 && $wkg > 0) {
                                        $gramasiVal = number_format(($wkg * 1000.0) / $q, 3, '.', '');
                                    }
                                }
                            @endphp
                            <td><input type="number" step="0.001" name="items[{{ $idx }}][gramasi]" value="{{ $gramasiVal }}" class="form-control"></td>
                            <td><input type="number" name="items[{{ $idx }}][unit_price_idr]" value="{{ $it['unit_price_idr'] ?? '' }}" class="form-control"></td>
                            <td><input type="number" name="items[{{ $idx }}][total_idr]" value="{{ $it['total_idr'] ?? '' }}" class="form-control"></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">Hapus</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">+ Tambah Item</button>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Catatan & Raw Text</div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6"><label class="form-label">Raw Text</label><textarea name="raw_text" class="form-control" rows="4">{{ old('raw_text', $document->raw_text) }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.master.faktur.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.min.js"></script>
<script>
const workerUrl="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.worker.min.js";if(window['pdfjsLib']){pdfjsLib.GlobalWorkerOptions.workerSrc=workerUrl;}
const addItemBtn=document.getElementById('addItemBtn');
if(addItemBtn){addItemBtn.addEventListener('click',function(){const tbody=document.querySelector('#itemsTable tbody');const idx=tbody.children.length;const tr=document.createElement('tr');tr.innerHTML=`<td><input type="number" name="items[${idx}][no]" class="form-control"></td><td><input type="text" name="items[${idx}][description]" class="form-control"></td><td><input type="number" name="items[${idx}][quantity_pcs]" class="form-control"></td><td><input type="number" step="0.000001" name="items[${idx}][weight_kg]" class="form-control"></td><td><input type="number" step="0.001" name="items[${idx}][gramasi]" class="form-control"></td><td><input type="number" name="items[${idx}][unit_price_idr]" class="form-control"></td><td><input type="number" name="items[${idx}][total_idr]" class="form-control"></td><td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">Hapus</button></td>`;tbody.appendChild(tr);});}
const toUint8=(b)=>new Uint8Array(b);const setVal=(n,v)=>{const el=document.querySelector(`[name="${n}"]`);if(el){el.value=v??'';}};
const parseGramasiFromDesc=(s)=>{const m=(s||'').match(/@\\s*([\\d.,]+)\\s*(?:gr\.?|gram)\\b/i);return m?m[1].replace(',','.'):'';};
const itemsTable=document.getElementById('itemsTable');
if(itemsTable){itemsTable.addEventListener('input',function(e){const t=e.target;if(!t||!t.name)return;const tr=t.closest('tr');if(!tr)return;if(t.name.includes('[description]')){const gInput=tr.querySelector('input[name^="items"][name$="[gramasi]"]');if(gInput){let g=parseGramasiFromDesc(t.value);if(!g){const wInput=tr.querySelector('input[name^="items"][name$="[weight_kg]"]');const qInput=tr.querySelector('input[name^="items"][name$="[quantity_pcs]"]');const w=parseFloat(wInput&&wInput.value?wInput.value:0);const q=parseInt(qInput&&qInput.value?qInput.value:0,10);if(q>0&&!Number.isNaN(w)&&w>0){g=(w*1000/q).toFixed(3);}}gInput.value=g||'';}}});}
const fillItems=(items)=>{const tbody=document.querySelector('#itemsTable tbody');tbody.innerHTML='';items.forEach((it,i)=>{const tr=document.createElement('tr');let g=parseGramasiFromDesc(it.description||'');const w=parseFloat(it.weight_kg||'');const q=parseInt(it.quantity_pcs||'',10);if((!g||g==='')&&q>0&&!Number.isNaN(w)&&w>0){g=(w*1000/q).toFixed(3);}tr.innerHTML=`<td><input type="number" name="items[${i}][no]" value="${it.no||''}" class="form-control"></td><td><input type="text" name="items[${i}][description]" value="${it.description||''}" class="form-control"></td><td><input type="number" name="items[${i}][quantity_pcs]" value="${it.quantity_pcs||''}" class="form-control"></td><td><input type="number" step="0.000001" name="items[${i}][weight_kg]" value="${it.weight_kg||''}" class="form-control"></td><td><input type="number" step="0.001" name="items[${i}][gramasi]" value="${g}" class="form-control"></td><td><input type="number" name="items[${i}][unit_price_idr]" value="${it.unit_price_idr||''}" class="form-control"></td><td><input type="number" name="items[${i}][total_idr]" value="${it.total_idr||''}" class="form-control"></td><td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">Hapus</button></td>`;tbody.appendChild(tr);});};
const joinItems=(items)=>items.map(i=>i.str).join('');
const extractTextFromPage=async(pdf,p)=>{const page=await pdf.getPage(p);const t=await page.getTextContent();return joinItems(t.items);};
const extractAllText=async(pdf)=>{const n=pdf.numPages;const parts=[];for(let i=1;i<=n;i++){parts.push(await extractTextFromPage(pdf,i));}return parts.join('\n');};
const toIdr=(s)=>{const n=(s||'').toString().replace(/[^0-9]/g,'');return n?parseInt(n,10):0;};
const toFloat=(s)=>{const n=(s||'').toString().replace(/[^0-9.]/g,'');return n?parseFloat(n):0;};
const parseItems=(raw)=>{const items=[];const re=/(\d+)\s+([A-Za-zÀ-ÿ0-9@().,\/\- ]+?)\s+(\d+)\s+([0-9.,]+)\s+([0-9.,]+)\s+([0-9.,]+)/g;let m;while((m=re.exec(raw))){items.push({no:parseInt(m[1],10),description:m[2].trim(),quantity_pcs:parseInt(m[3],10),weight_kg:toFloat(m[4]),unit_price_idr:toIdr(m[5]),total_idr:toIdr(m[6])});}return items;};
const parseTotals=(raw)=>{const gt=raw.match(/Grand\s+Total\s+([0-9.,]+)/i);const dpp=raw.match(/\bDPP\b\s+([0-9.,]+)/i);const pr=raw.match(/PPN\s*(\d+)%/i);const pn=raw.match(/PPN(?:\s*\d+%)?\s+([0-9.,]+)/i);return{grand_total_idr:gt?toIdr(gt[1]):0,dpp_idr:dpp?toIdr(dpp[1]):0,ppn_rate:pr?parseInt(pr[1],10):null,ppn_idr:pn?toIdr(pn[1]):0,currency:'IDR'};};
const parseIssuer=(raw)=>{const phone=raw.match(/Telp\.\s*\(([^)]+)\)/i);const website=/www\.logammulia\.com/i.test(raw)?'www.logammulia.com':null;const addr=raw.match(/Gedung\s+Graha\s+Dipta[\s\S]*?Jakarta\s+Timur\s+\d{5}/i);const np=raw.match(/NPWP\s+Pemungut\s*:\s*([^\-–]+)[\-–]\s*([0-9.\-]+)\s*([\s\S]*?)(?:Enam|Total|Dokumen)/i);return{company:'PT ANTAM Tbk',business_unit:'UNIT BISNIS PENGOLAHAN DAN PEMURNIAN LOGAM MULIA',address:addr?addr[0].replace(/\s+/g,' ').trim():null,website,phone:phone?phone[1]:null,npwp:np?np[2].trim():null,npwp_holder:np?np[1].trim():null,npwp_address:np?np[3].replace(/\s+/g,' ').trim():null};};
const parseAuthorized=(raw)=>{const m=raw.match(/([A-Za-z ]{3,})\s*(\d{16})/);return{name:m?m[1].trim():null,nik:m?m[2]:null};};
const parseDateIso=(s)=>{const map={Januari:1,Februari:2,Maret:3,April:4,Mei:5,Juni:6,Juli:7,Agustus:8,September:9,Oktober:10,November:11,Desember:12};const m=(s||'').match(/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/);if(!m)return null;const d=m[1].padStart(2,'0');const mon=map[m[2]]?String(map[m[2]]).padStart(2,'0'):null;const y=m[3];return mon?`${y}-${mon}-${d}`:null;};
const parseInvoiceHeader=(raw)=>{const num=raw.match(/\b[A-Z]{2}\d{6,}\b/);const ref=raw.match(/\b\d{6,}\s+[A-Z]{3}\d{5,}\b/);const date=raw.match(/\b(?:Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu),\s*\d{1,2}\s+[A-Za-z]+\s+\d{4}\b/);const trx=raw.match(/Jenis\s+Transaksi\s*:\s*(.+)/i);const dr=date?date[0]:null;return{number:num?num[0]:null,reference:ref?ref[0]:null,date_raw:dr,date_iso:parseDateIso(dr),transaction_type:trx?trx[1].trim():null};};
const parseCustomer=(raw)=>{const mem=raw.match(/(\d{6,})\s*-\s*([A-Z]+)/);const name=raw.match(/\b[A-Z][a-zA-Z]+\b(?=[^\S\r\n]*\d{6,}\s*-)/);return{name:name?name[0]:null,membership:{number:mem?mem[1]:null,tier:mem?mem[2]:null}};};
const parseService=(raw)=>{const svc=/Pengambilan\s+di\s+Butik/i.test(raw)?'Pengambilan di Butik':null;const boutique=(raw.match(/\b[A-Z]{3,}\s+[A-Za-z]+\b/)||[null])[0];const loc=(raw.match(/Summarecon[\s\S]*?JAWA\s+BARAT\s+\d{5}/i)||[null])[0];return{name:svc,boutique:{code_name:boutique,location:loc?loc.replace(/\s+/g,' ').trim():null}};};
const parsePayment=(raw)=>{const method=(/Virtual\s+Account\s+Mandiri/i.test(raw)?'Virtual Account Mandiri':null);const va=(raw.match(/\b\d{13,}\b/)||[null])[0];const payNo=(raw.match(/\bS\d{7,}\b/)||[null])[0];const created=(raw.match(/Mobile\s+App\s*-\s*\d{2}\/\d{2}\/\d{4}\s+\d{2}:\d{2}:\d{2}\s+WIB/i)||[null])[0];return{method,virtual_account:va,payment_no:payNo,created_by:created,print_by:null};};
const parseNotes=(raw)=>{const n=[];const m=raw.match(/Dokumen\s+ini\s+sah[\s\S]*?PT\s+ANTAM\s+Tbk/i);if(m)n.push(m[0].replace(/\s+/g,' ').trim());return n;};
const parseAll=(raw)=>({issuer:parseIssuer(raw),authorized_receiver:parseAuthorized(raw),invoice:parseInvoiceHeader(raw),customer:parseCustomer(raw),service:parseService(raw),items:parseItems(raw),totals:parseTotals(raw),payment:parsePayment(raw),notes:parseNotes(raw),raw_text:raw});
const fillFormFromDoc=(doc)=>{const i=doc.invoice||{};const c=doc.customer||{};const s=doc.service||{};const t=doc.totals||{};const p=doc.payment||{};const iss=doc.issuer||{};const butikMatch=(doc.raw_text||'').match(/Butik::::([A-Za-z0-9]+)/i);const invFromButik=butikMatch?butikMatch[1].slice(0,14):null;const inv=(invFromButik||i.number||(((doc.raw_text||'').match(/No\s+Faktur\s*([A-Za-z0-9-]+)/i)||[])[1])||'');setVal('issuer_company',iss.company);setVal('issuer_business_unit',iss.business_unit);setVal('issuer_address',iss.address);setVal('issuer_website',iss.website);setVal('issuer_phone',iss.phone);setVal('issuer_npwp',iss.npwp);setVal('issuer_npwp_holder',iss.npwp_holder);setVal('issuer_npwp_address',iss.npwp_address);setVal('authorized_receiver_name',(doc.authorized_receiver||{}).name);setVal('authorized_receiver_nik',(doc.authorized_receiver||{}).nik);setVal('invoice_number',inv);setVal('reference',i.reference);setVal('transaction_type',(i.transaction_type||'').slice(0,100));setVal('date_raw',i.date_raw);setVal('date',i.date_iso);setVal('customer_name',c.name);setVal('membership_number',(c.membership||{}).number);setVal('membership_tier',(c.membership||{}).tier);setVal('service_name',s.name);setVal('boutique_code_name',(s.boutique||{}).code_name);setVal('boutique_location',(s.boutique||{}).location);setVal('grand_total_idr',t.grand_total_idr);setVal('dpp_idr',t.dpp_idr);setVal('ppn_rate',t.ppn_rate);setVal('ppn_idr',t.ppn_idr);setVal('currency',t.currency);setVal('payment_method',p.method);setVal('virtual_account',p.virtual_account);setVal('payment_no',p.payment_no);setVal('created_by',p.created_by);setVal('print_by',p.print_by);setVal('raw_text',doc.raw_text);setVal('notes',JSON.stringify(doc));fillItems(doc.items||[]);};
const statusEl=document.getElementById('fakturParseStatus');
const handleFile=async(file)=>{try{statusEl.textContent='Memproses PDF...';const buf=await file.arrayBuffer();const pdf=await pdfjsLib.getDocument({data:toUint8(buf)}).promise;const text=await extractAllText(pdf);fillFormFromDoc(parseAll(text));statusEl.textContent='Berhasil diparse dan diisi.';}catch(e){statusEl.textContent='Gagal memproses PDF.';}}
const input=document.getElementById('fakturPdfInput');
if(input){input.addEventListener('change',async(e)=>{const f=e.target.files&&e.target.files[0];if(!f)return;await handleFile(f);});}
</script>