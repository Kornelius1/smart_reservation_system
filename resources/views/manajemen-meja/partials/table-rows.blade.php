@forelse($meja as $item)
<tr class="hover:bg-gray-50 transition-colors">
    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nomor_meja }}</td>
    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->kapasitas_format }}</td>
    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->lokasi }}</td>
    <td class="px-6 py-4 text-sm">
        @if($item->status_aktif)
            <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                Available
            </span>
        @else
            <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                Not Available
            </span>
        @endif
    </td>
    <td class="px-6 py-4 text-center">
        <!-- Toggle Switch sesuai gambar -->
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" 
                   class="sr-only toggle-status" 
                   data-id="{{ $item->id }}"
                   {{ $item->status_aktif ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
        </label>
        
        <!-- Dropdown Action Menu -->
        <div class="relative inline-block ml-4">
            <button class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none" onclick="toggleDropdown({{ $item->id }})">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                </svg>
            </button>
            
            <div id="dropdown-{{ $item->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-10 border">
                <div class="py-1">
                    <button class="btn-edit w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-gray-100 flex items-center gap-2" 
                            data-id="{{ $item->id }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </button>
                    <button class="btn-hapus w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-2" 
                            data-id="{{ $item->id }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="px-6 py-8 text-center">
        <div class="flex flex-col items-center">
            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <p class="text-gray-500">Tidak ada data meja</p>
        </div>
    </td>
</tr>
@endforelse