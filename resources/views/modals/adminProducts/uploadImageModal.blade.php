<div class="modal fade" id="uploadImagesModal" tabindex="-1" aria-labelledby="uploadImagesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadImagesModalLabel">Nahrát Obrázky</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadImagesForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="imagesInput" class="form-label">Vyberte obrázky k nahrání</label>
                            <input type="file" class="form-control" id="imagesInput" name="photos[]" multiple>
                        </div>
                        <div id="progressBarsContainer">
                        </div>
                        <button type="submit" class="btn btn-primary">Nahrát</button>
                    </form>
                </div>
            </div>
        </div>
    </div>