<div class="modal fade" id="accessConfirmationModal" tabindex="-1" aria-labelledby="accessConfirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accessConfirmationModalLabel">Potvrzení Přístupu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Dostali jste se dovnitř prodejny?</p>
                <div id="initialOptions" style="display: block;">
                    <button type="button" class="btn btn-success confirm-access" id="confirmAccess">Ano</button>
                    <button type="button" class="btn btn-danger" id="denyAccess">Ne</button>
                </div>
                <div id="additionalOptions" style="display: none;">
                    <p>Nepodařilo se dostat dovnitř? Zkuste to znovu, nebo kontaktujte podporu.</p>
                    <button type="button" class="btn btn-primary" id="retryScan">Zkusit znovu</button>
                    <a href="/contact" class="btn btn-secondary">Kontaktovat podporu</a>
                </div>
                <form id="storeRedirectForm" action="/shopping" method="POST" style="display:none;">
                    @csrf
                    <input type="hidden" name="store_id" id="hiddenStoreId">
                </form>
            </div>
        </div>
    </div>
</div>
