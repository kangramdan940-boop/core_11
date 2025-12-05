<div class="menubar-footer footer-fixed" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 1030;">
    <ul class="inner-bar">
        <li title="{{ $active }}" class="{{ ($active ?? '') === 'dashboard' ? 'active active-tab' : '' }}">
            <a href="{{ route('customer.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 19v-6.733a4 4 0 0 0-1.245-2.9L13.378 3.31a2 2 0 0 0-2.755 0L4.245 9.367A4 4 0 0 0 3 12.267V19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2"/></svg>
            </a>
        </li>
        <li class="{{ ($active ?? '') === 'produk' ? 'active active-tab' : '' }}">
            <a href="{{ route('customer.product-dan-layanan') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><g fill="none" stroke="currentColor" strokeWidth={1.5}><path d="M14.264 18.372c.317-.965.475-1.447.798-1.779a2 2 0 0 1 .527-.386C16 16 16.5 16 17.5 16s1.5 0 1.912.207q.294.148.527.386c.323.332.48.814.797 1.779l.326.995c.394 1.202.591 1.802.297 2.218c-.295.415-.917.415-2.162.415h-3.393c-1.245 0-1.868 0-2.162-.415c-.295-.416-.098-1.016.296-2.218zm-5.5-8c.316-.965.475-1.447.797-1.779q.233-.238.527-.386C10.5 8 11 8 12 8s1.5 0 1.912.207q.294.148.527.386c.322.332.48.814.797 1.779l.326.995c.394 1.202.59 1.802.297 2.218c-.295.415-.917.415-2.163.415h-3.392c-1.246 0-1.868 0-2.162-.415c-.295-.416-.098-1.016.296-2.218zm-5.5 8c.317-.965.475-1.447.798-1.779q.231-.238.527-.386C5 16 5.5 16 6.5 16s1.5 0 1.912.207q.294.148.527.386c.323.332.48.814.797 1.779l.326.995c.394 1.202.591 1.802.297 2.218c-.295.415-.917.415-2.162.415H4.804c-1.245 0-1.868 0-2.162-.415c-.295-.416-.098-1.016.296-2.218z"></path><path strokeLinecap="round" d="M12 2v2m-4.5-.5L9 5m7.5-1.5L15 5"></path></g></svg>
            </a>
        </li>
        <li class="action-add {{ ($active ?? '') === 'pre-order-emas' ? 'center-active' : '' }}"><a href="{{ $poHref ?? 'pre-order-emas' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H18" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 18V6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a></li>
        <li class="{{ ($active ?? '') === 'all-order' ? 'active active-tab' : '' }}">
            <a href="{{ route('customer.all-order') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width={36} height={36} viewBox="0 0 36 36"><path fill="currentColor" d="M33.49 26.28a1 1 0 0 0-1.2-.7l-2.49.67a14.23 14.23 0 0 0 2.4-6.75a14.48 14.48 0 0 0-4.83-12.15a1 1 0 0 0-1.37.09a1 1 0 0 0 .09 1.41a12.45 12.45 0 0 1 4.16 10.46a12.2 12.2 0 0 1-2 5.74L28 22.54a1 1 0 1 0-1.95.16l.5 6.44l6.25-1.66a1 1 0 0 0 .69-1.2"></path><path fill="currentColor" d="M4.31 17.08a1.1 1.1 0 0 0 .44.16a1 1 0 0 0 1.12-.85A12.21 12.21 0 0 1 18.69 5.84l-2.24 1.53a1 1 0 0 0 .47 1.79a1 1 0 0 0 .64-.16l5.33-3.66L18.33.76a1 1 0 1 0-1.39 1.38l1.7 1.7A14.2 14.2 0 0 0 3.89 16.12a1 1 0 0 0 .42.96"></path><path fill="currentColor" d="M21.73 29.93a12 12 0 0 1-4.84.51a12.3 12.3 0 0 1-9.57-6.3l2.49.93a1 1 0 0 0 .69-1.84l-4.59-1.7L4.44 21l-1.11 6.35a1 1 0 0 0 .79 1.13h.17a1 1 0 0 0 1-.81l.42-2.4a14.3 14.3 0 0 0 11 7.14a13.9 13.9 0 0 0 5.63-.6a1 1 0 0 0-.6-1.9Z"></path><path fill="currentColor" d="M22 13h-8a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-8a1 1 0 0 0-1-1m-1 8h-6v-6h6Z"></path><path fill="none" d="M0 0h36v36H0z"></path></svg>
            </a>
        </li>
        <li class="{{ ($active ?? '') === 'profile' ? 'active active-tab' : '' }}">
            <a href="{{ route('customer.profile') }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.6">
                        <path d="M12.1601 10.87C12.0601 10.86 11.9401 10.86 11.8301 10.87C9.45006 10.79 7.56006 8.84 7.56006 6.44C7.56006 3.99 9.54006 2 12.0001 2C14.4501 2 16.4401 3.99 16.4401 6.44C16.4301 8.84 14.5401 10.79 12.1601 10.87Z" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.16021 14.56C4.74021 16.18 4.74021 18.82 7.16021 20.43C9.91021 22.27 14.4202 22.27 17.1702 20.43C19.5902 18.81 19.5902 16.17 17.1702 14.56C14.4302 12.73 9.92021 12.73 7.16021 14.56Z" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                </svg>
            </a>
        </li>
    </ul>
</div>