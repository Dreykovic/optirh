/**
 * Publications Edit Form - File Management
 * OptiHR - Espace Collaboratif
 */

document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 MB
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

    // Store for each publication's new files
    const publicationFiles = {};

    // Initialize all edit modals
    initializeEditModals();

    /**
     * Initialize all publication edit modals
     */
    function initializeEditModals() {
        const editModals = document.querySelectorAll('[id^="publicationEdit"]');

        editModals.forEach(modal => {
            const publicationId = modal.id.replace('publicationEdit', '');
            initializeModal(publicationId);
        });
    }

    /**
     * Initialize a single modal
     */
    function initializeModal(publicationId) {
        publicationFiles[publicationId] = [];

        const dropZone = document.getElementById(`editDropZone${publicationId}`);
        const fileInput = document.getElementById(`editFile${publicationId}`);
        const clearBtn = document.querySelector(`.clear-new-files[data-publication-id="${publicationId}"]`);
        const form = document.querySelector(`#publicationUpdateForm${publicationId} form`);

        // Initialize existing file removal
        initializeExistingFileRemoval(publicationId);

        // Drop zone events
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false);
            });

            dropZone.addEventListener('drop', (e) => handleDrop(e, publicationId), false);
        }

        // File input change
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    handleFiles(Array.from(this.files), publicationId);
                }
            });
        }

        // Clear all new files
        if (clearBtn) {
            clearBtn.addEventListener('click', () => clearNewFiles(publicationId));
        }

        // Sync files before form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                updateFileInput(publicationId);
            });
        }

        // Reset on modal close
        const modal = document.getElementById(`publicationEdit${publicationId}`);
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => resetModal(publicationId));
        }
    }

    /**
     * Initialize existing file removal buttons
     */
    function initializeExistingFileRemoval(publicationId) {
        const existingFilesContainer = document.getElementById(`existingFiles${publicationId}`);
        if (!existingFilesContainer) return;

        existingFilesContainer.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-existing-file');
            if (!removeBtn) return;

            const fileId = removeBtn.dataset.fileId;
            const fileItem = removeBtn.closest('.existing-file-item');
            const filesToDeleteInput = document.getElementById(`filesToDelete${publicationId}`);

            // Toggle removal state
            if (fileItem.classList.contains('removing')) {
                // Restore file
                fileItem.classList.remove('removing');
                removeFileIdFromList(filesToDeleteInput, fileId);
            } else {
                // Mark for removal
                fileItem.classList.add('removing');
                addFileIdToList(filesToDeleteInput, fileId);
            }
        });
    }

    /**
     * Add file ID to deletion list
     */
    function addFileIdToList(input, fileId) {
        const currentValue = input.value;
        const ids = currentValue ? currentValue.split(',').filter(id => id) : [];
        if (!ids.includes(fileId)) {
            ids.push(fileId);
        }
        input.value = ids.join(',');
    }

    /**
     * Remove file ID from deletion list
     */
    function removeFileIdFromList(input, fileId) {
        const currentValue = input.value;
        const ids = currentValue ? currentValue.split(',').filter(id => id && id !== fileId) : [];
        input.value = ids.join(',');
    }

    /**
     * Prevent default drag behaviors
     */
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    /**
     * Handle dropped files
     */
    function handleDrop(e, publicationId) {
        const files = e.dataTransfer.files;
        handleFiles(Array.from(files), publicationId);
    }

    /**
     * Process and validate files
     */
    function handleFiles(files, publicationId) {
        const validFiles = [];
        const errors = [];

        files.forEach(file => {
            // Check file type
            if (!ALLOWED_TYPES.includes(file.type)) {
                errors.push(`${file.name}: Type de fichier non autorisé`);
                return;
            }

            // Check file size
            if (file.size > MAX_FILE_SIZE) {
                errors.push(`${file.name}: Fichier trop volumineux (max 10 Mo)`);
                return;
            }

            // Check for duplicates
            const isDuplicate = publicationFiles[publicationId].some(f =>
                f.name === file.name && f.size === file.size
            );

            if (isDuplicate) {
                errors.push(`${file.name}: Fichier déjà sélectionné`);
                return;
            }

            validFiles.push(file);
        });

        // Show errors if any
        if (errors.length > 0) {
            showToast(errors.join('<br>'), 'warning');
        }

        // Add valid files
        if (validFiles.length > 0) {
            publicationFiles[publicationId] = [...publicationFiles[publicationId], ...validFiles];
            updateFileList(publicationId);
            updateFileInput(publicationId);
        }
    }

    /**
     * Update the new files list display
     */
    function updateFileList(publicationId) {
        const fileList = document.getElementById(`newFileList${publicationId}`);
        const fileListItems = document.getElementById(`newFileListItems${publicationId}`);
        const fileCount = document.getElementById(`newFileCount${publicationId}`);
        const files = publicationFiles[publicationId];

        if (files.length === 0) {
            fileList.style.display = 'none';
            return;
        }

        fileList.style.display = 'block';
        fileCount.textContent = files.length;
        fileListItems.innerHTML = '';

        files.forEach((file, index) => {
            const fileItem = createFileItem(file, index, publicationId);
            fileListItems.appendChild(fileItem);
        });
    }

    /**
     * Create a file item element
     */
    function createFileItem(file, index, publicationId) {
        const item = document.createElement('div');
        item.className = 'file-item d-flex align-items-center p-2 border rounded bg-success bg-opacity-10';
        item.dataset.index = index;

        // Determine icon based on file type
        let iconClass = 'icofont-file-alt text-warning';
        if (file.type === 'application/pdf') {
            iconClass = 'icofont-file-pdf text-danger';
        } else if (file.type.startsWith('image/')) {
            iconClass = 'icofont-image text-success';
        }

        // Format file size
        const fileSize = formatFileSize(file.size);

        item.innerHTML = `
            <i class="${iconClass} me-2"></i>
            <div class="file-info flex-grow-1 overflow-hidden">
                <div class="file-name text-truncate small fw-medium" title="${file.name}">${file.name}</div>
                <div class="file-size text-muted" style="font-size: 0.7rem;">${fileSize}</div>
            </div>
            <button type="button" class="btn btn-sm text-danger ms-2 remove-new-file" data-index="${index}" title="Supprimer">
                <i class="icofont-close-line"></i>
            </button>
        `;

        // Add remove handler
        const removeBtn = item.querySelector('.remove-new-file');
        removeBtn.addEventListener('click', () => removeNewFile(index, publicationId));

        return item;
    }

    /**
     * Remove a new file from selection
     */
    function removeNewFile(index, publicationId) {
        publicationFiles[publicationId].splice(index, 1);
        updateFileList(publicationId);
        updateFileInput(publicationId);
    }

    /**
     * Clear all new files
     */
    function clearNewFiles(publicationId) {
        publicationFiles[publicationId] = [];
        updateFileList(publicationId);
        updateFileInput(publicationId);
    }

    /**
     * Update the file input with selected files
     */
    function updateFileInput(publicationId) {
        const fileInput = document.getElementById(`editFile${publicationId}`);
        if (!fileInput) return;

        const dt = new DataTransfer();
        publicationFiles[publicationId].forEach(file => {
            dt.items.add(file);
        });
        fileInput.files = dt.files;
    }

    /**
     * Reset modal state
     */
    function resetModal(publicationId) {
        // Clear new files
        publicationFiles[publicationId] = [];
        updateFileList(publicationId);

        // Reset files to delete
        const filesToDeleteInput = document.getElementById(`filesToDelete${publicationId}`);
        if (filesToDeleteInput) {
            filesToDeleteInput.value = '';
        }

        // Restore existing files appearance
        const existingFiles = document.querySelectorAll(`#existingFiles${publicationId} .existing-file-item`);
        existingFiles.forEach(item => item.classList.remove('removing'));
    }

    /**
     * Format file size
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'Ko', 'Mo', 'Go'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'warning' ? 'Attention' : 'Information',
                html: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        } else {
            alert(message.replace(/<br>/g, '\n'));
        }
    }
});
