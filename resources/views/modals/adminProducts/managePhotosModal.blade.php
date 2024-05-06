<div class="modal fade" id="managePhotosModal" tabindex="-1" aria-labelledby="managePhotosModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="managePhotosModalLabel">Správa fotografií</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchPhotoAlias" class="form-control mb-3"
                        placeholder="Vyhledejte podle aliasu...">
                    <div id="photosContainer" class="row">
                        <table id="photosTable" class="table">
                            <thead>
                                <tr>
                                    <th>Náhled</th>
                                    <th>Alias</th>
                                    <th>Akce</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="modal-footer">
                    <button id="deleteSelectedPhotos" class="btn btn-danger">Smazat označené</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>