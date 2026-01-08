@extends('layouts.admin.master')

@section('title', 'Tambah Stok Emas - Admin')
@section('sub-title', 'Master')
@section('breadcrumbExtra', 'Tambah Stok Emas')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.gold-stocks.index'))

@section('content')
    <form action="{{ route('admin.master.gold-stocks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.master_gold_stock._form', ['stock' => null, 'mitras' => $mitras])
        <textarea name="parsed_document_json" id="parsedDocumentJson" class="d-none"></textarea>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.master.gold-stocks.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-header">Pembaca PDF</div>
        <div class="card-body">
            <div class="mb-3">
                <input type="file" id="pdfInput" accept="application/pdf" class="form-control">
            </div>
            <div id="pdfMeta" class="mb-2"></div>
            <pre id="pdfText" class="form-control" style="min-height:200px;white-space:pre-wrap"></pre>
            <div class="mt-3">
                <label class="form-label fw-semibold">Hasil JSON</label>
                <pre id="pdfJson" class="form-control" style="min-height:160px;white-space:pre-wrap"></pre>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.min.js"></script>
    <script>
    const workerUrl="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.worker.min.js";
    if (window['pdfjsLib']) { pdfjsLib.GlobalWorkerOptions.workerSrc=workerUrl; }
    const toUint8=(buf)=>new Uint8Array(buf);
    const joinItems=(items)=>items.map(i=>i.str).join('');
    const extractTextFromPage=async (pdf,pageNumber)=>{ const page=await pdf.getPage(pageNumber); const text=await page.getTextContent(); return joinItems(text.items); };
    const extractAllText=async (pdf)=>{ const n=pdf.numPages; const parts=[]; for(let i=1;i<=n;i++){ const t=await extractTextFromPage(pdf,i); parts.push(t); } return parts.join('\n'); };
    const formatMeta=async (pdf)=>{ const d=await pdf.getMetadata().catch(()=>({info:{}})); const info=d&&d.info?d.info:{}; const title=info.Title||''; const author=info.Author||''; return {pages:pdf.numPages,title,author}; };
    const showMeta=(m)=>{ const e=document.getElementById('pdfMeta'); e.textContent=`Halaman: ${m.pages}${m.title?` • Judul: ${m.title}`:''}${m.author?` • Penulis: ${m.author}`:''}`; };
    const showText=(t)=>{ const e=document.getElementById('pdfText'); e.textContent=t||''; };
    const showJson=(obj)=>{ const s=JSON.stringify(obj, null, 2); const e=document.getElementById('pdfJson'); if(e) e.textContent=s; const h=document.getElementById('parsedDocumentJson'); if(h) h.value=s; };
    const toIdr=(s)=>{ const n=(s||'').toString().replace(/[^0-9]/g,''); return n?parseInt(n,10):0; };
    const toFloat=(s)=>{ const n=(s||'').toString().replace(/[^0-9.]/g,''); return n?parseFloat(n):0; };
    const parseItems=(raw)=>{ const items=[]; const re=/(\d+)\s+([A-Za-zÀ-ÿ0-9@().,\/\- ]+?)\s+(\d+)\s+([0-9.,]+)\s+([0-9.,]+)\s+([0-9.,]+)/g; let m; while((m=re.exec(raw))){ items.push({ no:parseInt(m[1],10), description:m[2].trim(), quantity_pcs:parseInt(m[3],10), weight_kg:toFloat(m[4]), unit_price_idr:toIdr(m[5]), total_idr:toIdr(m[6]) }); } return items; };
    const parseTotals=(raw)=>{ const gtMatch=raw.match(/Grand\s+Total\s+([0-9.,]+)/i); const dppMatch=raw.match(/\bDPP\b\s+([0-9.,]+)/i); const ppnRateMatch=raw.match(/PPN\s*(\d+)%/i); const ppnMatch=raw.match(/PPN(?:\s*\d+%)?\s+([0-9.,]+)/i); return { grand_total_idr: gtMatch?toIdr(gtMatch[1]):0, dpp_idr: dppMatch?toIdr(dppMatch[1]):0, ppn_rate: ppnRateMatch?`${ppnRateMatch[1]}%`:null, ppn_idr: ppnMatch?toIdr(ppnMatch[1]):0, currency:'IDR' }; };
    const parseIssuer=(raw)=>{ const npwpMatch=raw.match(/NPWP\s+Pemungut\s*:\s*([^\-–]+)[\-–]\s*([0-9.\-]+)\s*([\s\S]*?)(?:Enam|Total|Dokumen)/i); const phoneMatch=raw.match(/Telp\.\s*\(([^)]+)\)/i); const websiteMatch=raw.match(/www\.logammulia\.com/i); const addrMatch=raw.match(/Gedung\s+Graha\s+Dipta[\s\S]*?Jakarta\s+Timur\s+\d{5}/i); return { company:'PT ANTAM Tbk', business_unit:'UNIT BISNIS PENGOLAHAN DAN PEMURNIAN LOGAM MULIA', address: addrMatch?addrMatch[0].replace(/\s+/g,' ').trim():null, website: websiteMatch?'www.logammulia.com':null, phone: phoneMatch?phoneMatch[1]:null, npwp: npwpMatch?npwpMatch[2].trim():null, npwp_holder: npwpMatch?npwpMatch[1].trim():null, npwp_address: npwpMatch?npwpMatch[3].replace(/\s+/g,' ').trim():null }; };
    const parseAuthorized=(raw)=>{ const m=raw.match(/([A-Za-z ]{3,})\s*(\d{16})/); return { name:m?m[1].trim():null, nik:m?m[2]:null }; };
    const parseDateIso=(dateRaw)=>{ const map={Januari:1,Februari:2,Maret:3,April:4,Mei:5,Juni:6,Juli:7,Agustus:8,September:9,Oktober:10,November:11,Desember:12}; const m=(dateRaw||'').match(/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/); if(!m) return null; const d=m[1].padStart(2,'0'); const mon=map[m[2]]?String(map[m[2]]).padStart(2,'0'):null; const y=m[3]; return mon?`${y}-${mon}-${d}`:null; };
    const parseInvoiceHeader=(raw)=>{ const numMatch=raw.match(/\b[A-Z]{2}\d{6,}\b/); const refMatch=raw.match(/\b\d{6,}\s+[A-Z]{3}\d{5,}\b/); const dateMatch=raw.match(/\b(?:Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu),\s*\d{1,2}\s+[A-Za-z]+\s+\d{4}\b/); const trxMatch=raw.match(/Jenis\s+Transaksi\s*:\s*(.+)/i); const dateRaw=dateMatch?dateMatch[0]:null; return { number:numMatch?numMatch[0]:null, reference:refMatch?refMatch[0]:null, date_raw:dateRaw, date_iso:parseDateIso(dateRaw), transaction_type:trxMatch?trxMatch[1].trim():null }; };
    const parseCustomer=(raw)=>{ const memMatch=raw.match(/(\d{6,})\s*-\s*([A-Z]+)/); const nameMatch=raw.match(/\b[A-Z][a-zA-Z]+\b(?=[^\S\r\n]*\d{6,}\s*-)/); return { name:nameMatch?nameMatch[0]:null, membership:{ number:memMatch?memMatch[1]:null, tier:memMatch?memMatch[2]:null } }; };
    const parseService=(raw)=>{ const svcMatch=raw.match(/Pengambilan\s+di\s+Butik/i); const boutiqueMatch=raw.match(/\b[A-Z]{3,}\s+[A-Za-z]+\b/); const locMatch=raw.match(/Summarecon[\s\S]*?JAWA\s+BARAT\s+\d{5}/i); return { name:svcMatch?svcMatch[0]:null, boutique:{ code_name:boutiqueMatch?boutiqueMatch[0]:null, location:locMatch?locMatch[0].replace(/\s+/g,' ').trim():null } }; };
    const parsePayment=(raw)=>{ const methodMatch=raw.match(/Virtual\s+Account\s+Mandiri/i); const vaMatch=raw.match(/\b\d{13,}\b/); const payNoMatch=raw.match(/\bS\d{7,}\b/); const createdByMatch=raw.match(/Mobile\s+App\s*-\s*\d{2}\/\d{2}\/\d{4}\s+\d{2}:\d{2}:\d{2}\s+WIB/i); return { method:methodMatch?methodMatch[0]:null, virtual_account:vaMatch?vaMatch[0]:null, payment_no:payNoMatch?payNoMatch[0]:null, created_by:createdByMatch?createdByMatch[0]:null, print_by:null }; };
    const parseNotes=(raw)=>{ const n=[]; const m=raw.match(/Dokumen\s+ini\s+sah[\s\S]*?PT\s+ANTAM\s+Tbk/i); if(m) n.push(m[0].replace(/\s+/g,' ').trim()); return n; };
    const parseAll=(raw)=>{ return { issuer:parseIssuer(raw), authorized_receiver:parseAuthorized(raw), invoice:parseInvoiceHeader(raw), customer:parseCustomer(raw), service:parseService(raw), items:parseItems(raw), totals:parseTotals(raw), payment:parsePayment(raw), notes:parseNotes(raw), raw_text:raw }; };
    const handleFile=async (file)=>{ const buf=await file.arrayBuffer(); const pdf=await pdfjsLib.getDocument({data:toUint8(buf)}).promise; const meta=await formatMeta(pdf); showMeta(meta); const text=await extractAllText(pdf); showText(text); showJson(parseAll(text)); };
    document.getElementById('pdfInput').addEventListener('change', async (e)=>{ const f=e.target.files&&e.target.files[0]; if(!f) return; await handleFile(f); });
    </script>
@endsection