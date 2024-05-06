<div class="modal fade" id="completeOrderModal" tabindex="-1" aria-labelledby="completeOrderLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeOrderLabel">Dokončit objednávku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Opravdu chcete dokončit objednávku?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
                <form action="/completeCheckout" method="POST">
                    @csrf
                    <input type="hidden" name="sessionId" value="{{ $sessionId }}">
                    <button type="submit" class="btn btn-primary">Ano, dokončit</button>
                </form>
            </div>
        </div>
    </div>
</div>
