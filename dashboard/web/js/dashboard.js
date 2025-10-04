// Dashboard JavaScript functionality
$(document).ready(function() {
    // Car listings page
    if ($('#cars-grid-container').length && $('#filter-form').length) {
        initCarListingsFilter();
        loadInitialGrid();
    }
    
    // Car form pages 
    if ($('#image-uploads-container').length) {
        initImageUpload();
    }
    
});

// Image Upload Functionality
let imageCount = 1;
let maxImages = 3;

function initImageUpload() {
    // Check if we're on a car form page
    if ($('#image-uploads-container').length) {
        // Set initial image count based on existing images
        const existingImages = $('.img-thumbnail').length;
        const fileInputs = $('input[type="file"][name*="imageFiles"]').length;
        
        // Use the higher of existing images or file inputs
        imageCount = Math.max(1, Math.max(existingImages, fileInputs));
        
        // Initialize button visibility
        checkAddButtonVisibility();
    }
}

function previewImage(input, imageNumber) {
    const preview = $('#preview-' + imageNumber);
    preview.empty();
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const $img = $('<img>').attr('src', e.target.result)
                    .css({
                        'width': '100%',
                        'height': '120px',
                        'object-fit': 'cover',
                        'border': '1px solid #ddd',
                        'border-radius': '5px'
                    });
                
                const removeBtn = $('<button>')
                    .attr('type', 'button')
                    .addClass('btn btn-sm btn-danger mt-1')
                    .html('<i class="fas fa-trash"></i> Remove')
                    .on('click', function() {
                        $(input).val('');
                        preview.empty();
                        setTimeout(checkAddButtonVisibility, 100);
                    });
                
                preview.append($img).append('<br>').append(removeBtn);
                
                checkAddButtonVisibility();
            };
            reader.readAsDataURL(file);
        } else {
            alert('Please select a valid image file.');
            $(input).val('');
        }
    } else {
        checkAddButtonVisibility();
    }
}

function addImageUpload() {
    if (imageCount >= maxImages) {
        alert('Maximum ' + maxImages + ' images allowed.');
        return;
    }
    
    imageCount++;
    const addBtnContainer = $('#add-image-btn-container');
    
    const newUploadDiv = $('<div>')
        .addClass('row mb-3')
        .attr('id', 'image-upload-' + imageCount)
        .html('<div class="col-md-8"><label class="form-label">Image ' + imageCount + '</label><input type="file" name="CarListing[imageFiles][]" class="form-control" accept="image/*" id="image-' + imageCount + '" onchange="previewImage(this, ' + imageCount + ')"></div><div class="col-md-4"><div id="preview-' + imageCount + '" class="mt-4"></div></div>');
    
    addBtnContainer.before(newUploadDiv);
    
    checkAddButtonVisibility();
}

function checkAddButtonVisibility() {
    const addBtnContainer = $('#add-image-btn-container');
    
    let filesSelected = 0;
    $('input[type="file"]').each(function() {
        if (this.files && this.files.length > 0) {
            filesSelected++;
        }
    });
    
    if (filesSelected > 0 && imageCount < maxImages) {
        addBtnContainer.show();
    } else {
        addBtnContainer.hide();
    }
}

// Make functions global for onclick handlers - Define immediately
window.previewImage = previewImage;
window.addImageUpload = addImageUpload;
window.checkAddButtonVisibility = checkAddButtonVisibility;

function initCarListingsFilter() {

    $('#clear-filter').on('click', function() {
        $('#filter-form')[0].reset();
        filterCars();
    });
    
    var filterTimeout;
    $('#filter-form input, #filter-form select').on('input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            filterCars();
        }, 500); 
    });
    
    initializeGridEvents();
}

function loadInitialGrid() {
    
    $.ajax({
        url: $('#filter-form').attr('action'),
        type: 'GET',
        success: function(response) {
            
            $('#cars-grid-container').replaceWith(response);
            initializeGridEvents();
        },
        error: function() {
            $('#cars-grid-container').html('<div class="alert alert-danger">Error loading data. Please refresh the page.</div>');
        }
    });
}

function filterCars() {
    // Show loading indicator
    $('#loading-indicator').show();
    $('#cars-grid-container').hide();

    
    var formData = $('#filter-form').serialize();

    
    $.ajax({
        url: $('#filter-form').attr('action'),
        type: 'GET',
        data: formData,
        success: function(response) {
            
            $('#cars-grid-container').replaceWith(response);

            var gridSummary = $('#cars-grid-container .summary').text();
            if (gridSummary && $('#results-summary').length) {
                $('#results-summary').text(gridSummary);
            }
            $('#loading-indicator').hide();
            $('#cars-grid-container').show();
            initializeGridEvents();
        },
        error: function(xhr, status, error) {
            $('#loading-indicator').hide();
            $('#cars-grid-container').show();
            alert('Error filtering cars. Please try again.');
        }
    });
}

function loadGrid(url) {
    if (!url) return;

    $('#loading-indicator').show();
    $('#cars-grid-container').hide();

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $('#cars-grid-container').replaceWith(response);

            var gridSummary = $('#cars-grid-container .summary').text();
            if (gridSummary && $('#results-summary').length) {
                $('#results-summary').text(gridSummary);
            }

            $('#loading-indicator').hide();
            $('#cars-grid-container').show();
            
            initializeGridEvents();
        },
        error: function() {
            $('#loading-indicator').hide();
            $('#cars-grid-container').show();
            
            alert('Error loading grid. Please try again.');
        }
    });
}
function initializeGridEvents() {

    $('#cars-grid-container .pagination a').off('click').on('click', function(e) {
        e.preventDefault();

        loadGrid($(this).attr('href'));
    });

    // Handle sorting links
    $('#cars-grid-container th a').off('click').on('click', function(e) {
        e.preventDefault();

        loadGrid($(this).attr('href'));
    });
}
