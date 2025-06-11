<!-- Deleted Users Modal -->
<div id="deletedUsersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pengguna yang Dihapus</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeDeletedUsersModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div id="deletedUsersContent">
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600">Memuat data...</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="closeDeletedUsersModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openDeletedUsersModal() {
    document.getElementById('deletedUsersModal').classList.remove('hidden');
    loadDeletedUsers();
}

function closeDeletedUsersModal() {
    document.getElementById('deletedUsersModal').classList.add('hidden');
}

function loadDeletedUsers() {
    const contentDiv = document.getElementById('deletedUsersContent');
    
    fetch('{{ route("users.index") }}?show_deleted=true', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.users && data.users.length > 0) {
            contentDiv.innerHTML = generateDeletedUsersTable(data.users);
        } else {
            contentDiv.innerHTML = `
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">Tidak ada pengguna yang dihapus</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        contentDiv.innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-500">Terjadi kesalahan saat memuat data</p>
            </div>
        `;
    });
}

function generateDeletedUsersTable(users) {
    const roleConfig = {
        'admin': { bg: 'bg-red-100', text: 'text-red-800', border: 'border-red-200' },
        'dosen': { bg: 'bg-green-100', text: 'text-green-800', border: 'border-green-200' },
        'mahasiswa': { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-200' },
        'tendik': { bg: 'bg-yellow-100', text: 'text-yellow-800', border: 'border-yellow-200' },
        'sarpras': { bg: 'bg-gray-100', text: 'text-gray-800', border: 'border-gray-200' },
        'teknisi': { bg: 'bg-purple-100', text: 'text-purple-800', border: 'border-purple-200' }
    };

    let tableHTML = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengguna</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peran</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dihapus</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    `;

    users.forEach(user => {
        const config = roleConfig[user.peran] || roleConfig['mahasiswa'];
        const deletedDate = new Date(user.deleted_at);
        
        tableHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200"
                                 src="${user.img_url ? '/storage/' + user.img_url : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.nama_lengkap) + '&background=random'}"
                                 alt="${user.nama_lengkap}">
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">${user.nama_lengkap}</div>
                            <div class="text-sm text-gray-500">@${user.nama_pengguna}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${config.bg} ${config.text} ${config.border}">
                        ${user.peran.charAt(0).toUpperCase() + user.peran.slice(1)}
                    </span>
                </td>
                <td class="px-4 py-4 text-sm text-gray-900">${user.email}</td>
                <td class="px-4 py-4 text-sm text-gray-500">
                    <div class="flex flex-col">
                        <span>${deletedDate.toLocaleDateString('id-ID')}</span>
                        <span class="text-xs text-gray-400">${deletedDate.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</span>
                    </div>
                </td>
                <td class="px-4 py-4 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <button onclick="restoreUser(${user.id_pengguna}, '${user.nama_lengkap}')"
                                class="inline-flex items-center p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-200"
                                title="Pulihkan Pengguna">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <button onclick="forceDeleteUser(${user.id_pengguna}, '${user.nama_lengkap}')"
                                class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200"
                                title="Hapus Permanen">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tableHTML += `
                </tbody>
            </table>
        </div>
    `;

    return tableHTML;
}

function restoreUser(userId, userName) {
    if (confirm(`Apakah Anda yakin ingin memulihkan pengguna ${userName}?`)) {
        fetch(`/users/${userId}/restore`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('Pengguna berhasil dipulihkan!', 'success');
                // Reload deleted users list
                loadDeletedUsers();
                // Optionally reload main page
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Gagal memulihkan pengguna', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        });
    }
}

function forceDeleteUser(userId, userName) {
    if (confirm(`PERINGATAN: Ini akan menghapus pengguna ${userName} secara PERMANEN. Data tidak dapat dipulihkan. Lanjutkan?`)) {
        fetch(`/users/${userId}/force-delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Pengguna berhasil dihapus permanen!', 'success');
                loadDeletedUsers();
            } else {
                showNotification('Gagal menghapus pengguna', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        });
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('deletedUsersModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeletedUsersModal();
    }
});
</script>