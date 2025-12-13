<div class="menubar-footer footer-fixed" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 1030;">
    <ul class="inner-bar">
           <li class="{{ ($active ?? '') === 'dashboard' ? 'active active-tab' : '' }}">
            <a href="{{ route('mitra.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 19v-6.733a4 4 0 0 0-1.245-2.9L13.378 3.31a2 2 0 0 0-2.755 0L4.245 9.367A4 4 0 0 0 3 12.267V19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2"/></svg>
            </a>
        </li>
        <li class="{{ ($active ?? '') === 'calendar' ? 'active active-tab' : '' }}">
            <a href="{{ route('mitra.calendar') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/></g></svg>
            </a>
        </li>
        <li class="action-add {{ ($active ?? '') === 'komisi-add' ? 'center-active' : '' }}"><a href="{{ $poHref ?? route('mitra.dashboard') }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H18" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 18V6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a></li>
        <li class="{{ ($active ?? '') === 'komisi' ? 'active active-tab' : '' }}">
            <a href="{{ route('mitra.komisi.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 10v8M12 6v12M17 13v5" stroke-linecap="round"/></g></svg>
            </a>
        </li>
        <li class="{{ ($active ?? '') === 'profil' ? 'active active-tab' : '' }}">
            <a href="{{ route('mitra.profile') }}">
                <svg width="24" height="24" viewBox="0 0 24 24"><g opacity="0.8"><path d="M12.16 10.87c-.1-.01-.22-.01-.33 0C9.45 10.79 7.56 8.84 7.56 6.44C7.56 3.99 9.54 2 12 2s4.44 1.99 4.44 4.44c-.01 2.4-1.9 4.35-4.28 4.43Z" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.16 14.56c-2.42 1.62-2.42 4.26 0 5.87c2.75 1.84 7.26 1.84 10.01 0c2.42-1.62 2.42-4.26 0-5.87c-2.74-1.83-7.24-1.83-10.01 0Z" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
            </a>
        </li>
    </ul>
</div>