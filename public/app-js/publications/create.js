/**
 * Publications Create Form - Drag & Drop + Preview
 * OptiHR - Espace Collaboratif
 */

document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');
    const fileList = document.getElementById('fileList');
    const fileListItems = document.getElementById('fileListItems');
    const fileCount = document.getElementById('fileCount');
    const clearAllBtn = document.getElementById('clearAllFiles');
    const titleInput = document.getElementById('title');
    const titleCharCount = document.getElementById('titleCharCount');
    const form = document.getElementById('modelAddForm');

    // Store selected files
    let selectedFiles = [];

    // Max file size in bytes (10 MB)
    const MAX_FILE_SIZE = 10 * 1024 * 1024;

    // Allowed file types
    const ALLOWED_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf'
    ];

    /**
     * Title character counter
     */
    if (titleInput && titleCharCount) {
        titleInput.addEventListener('input', function() {
            const length = this.value.length;
            titleCharCount.textContent = `${length}/255`;

            if (length > 240) {
                titleCharCount.classList.add('text-warning');
                titleCharCount.classList.remove('text-danger', 'text-muted');
            } else if (length >= 255) {
                titleCharCount.classList.add('text-danger');
                titleCharCount.classList.remove('text-warning', 'text-muted');
            } else {
                titleCharCount.classList.add('text-muted');
                titleCharCount.classList.remove('text-warning', 'text-danger');
            }
        });
    }

    /**
     * Drag & Drop Events
     */
    if (dropZone) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when dragging over
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        // Handle click to select files
        dropZone.addEventListener('click', function() {
            fileInput.click();
        });
    }

    /**
     * File input change event
     * We need to capture files before the input is potentially cleared
     */
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            // Only process if files were actually selected
            if (this.files && this.files.length > 0) {
                // Copy files to array before processing
                const newFiles = Array.from(this.files);
                handleFiles(newFiles);
            }
        });
    }

    /**
     * Clear all files button
     */
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            clearAllFiles();
        });
    }

    /**
     * Sync files before form submission
     */
    if (form) {
        form.addEventListener('submit', function(e) {
            // Ensure file input has all selected files before submission
            updateFileInput();
        });

        // Listen for custom event from post.js
        document.addEventListener('publicationCreated', function() {
            resetForm();
        });
    }

    /**
     * Prevent default drag behaviors
     */
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    /**
     * Highlight drop zone
     */
    function highlight() {
        dropZone.classList.add('drag-over');
    }

    /**
     * Remove highlight from drop zone
     */
    function unhighlight() {
        dropZone.classList.remove('drag-over');
    }

    /**
     * Handle dropped files
     */
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    /**
     * Process and validate files
     * @param {FileList|Array} files - Files to process
     */
    function handleFiles(files) {
        const validFiles = [];
        const errors = [];

        // Ensure we have an array
        const fileArray = Array.isArray(files) ? files : Array.from(files);

        fileArray.forEach(file => {
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
            const isDuplicate = selectedFiles.some(f =>
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
            selectedFiles = [...selectedFiles, ...validFiles];
            updateFileList();
            updateFileInput();
        }
    }

    /**
     * Update the file list display
     */
    function updateFileList() {
        if (selectedFiles.length === 0) {
            fileList.style.display = 'none';
            return;
        }

        fileList.style.display = 'block';
        fileCount.textContent = selectedFiles.length;
        fileListItems.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const fileItem = createFileItem(file, index);
            fileListItems.appendChild(fileItem);
        });
    }

    /**
     * Create a file item element
     */
    function createFileItem(file, index) {
        const item = document.createElement('div');
        item.className = 'file-item d-flex align-items-center p-2 border rounded';
        item.dataset.index = index;

        // Determine icon based on file type
        let iconClass = 'icofont-file-alt text-warning';
        let iconBg = 'bg-warning bg-opacity-10';

        if (file.type === 'application/pdf') {
            iconClass = 'icofont-file-pdf text-danger';
            iconBg = 'bg-danger bg-opacity-10';
        } else if (file.type.startsWith('image/')) {
            iconClass = 'icofont-image text-success';
            iconBg = 'bg-success bg-opacity-10';
        }

        // Create preview for images
        let previewHtml = '';
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const thumbnail = item.querySelector('.preview-thumbnail');
                if (thumbnail) {
                    thumbnail.src = e.target.result;
                    thumbnail.style.display = 'block';
                    item.querySelector('.file-icon-wrapper').style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
            previewHtml = `<img class="preview-thumbnail me-2 rounded" style="display:none; width:40px; height:40px; object-fit:cover;" alt="">`;
        }

        // Format file size
        const fileSize = formatFileSize(file.size);

        item.innerHTML = `
            ${previewHtml}
            <div class="file-icon-wrapper ${iconBg} rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                <i class="${iconClass}"></i>
            </div>
            <div class="file-info flex-grow-1 overflow-hidden">
                <div class="file-name text-truncate small fw-medium" title="${file.name}">${file.name}</div>
                <div class="file-size text-muted" style="font-size: 0.75rem;">${fileSize}</div>
            </div>
            <button type="button" class="btn btn-sm text-danger ms-2 remove-file" data-index="${index}" title="Supprimer">
                <i class="icofont-close-line"></i>
            </button>
        `;

        // Add remove handler
        const removeBtn = item.querySelector('.remove-file');
        removeBtn.addEventListener('click', function() {
            removeFile(index);
        });

        return item;
    }

    /**
     * Remove a file from selection
     */
    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileList();
        updateFileInput();
    }

    /**
     * Clear all selected files
     */
    function clearAllFiles() {
        selectedFiles = [];
        updateFileList();
        updateFileInput();
    }

    /**
     * Update the file input with selected files
     */
    function updateFileInput() {
        // Create a new DataTransfer object
        const dt = new DataTransfer();

        selectedFiles.forEach(file => {
            dt.items.add(file);
        });

        fileInput.files = dt.files;
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
     * Reset form after submission
     */
    function resetForm() {
        if (titleInput) titleInput.value = '';
        if (titleCharCount) titleCharCount.textContent = '0/255';

        const contentInput = document.getElementById('content');
        if (contentInput) contentInput.value = '';

        clearAllFiles();
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'info') {
        // Use SweetAlert if available
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
