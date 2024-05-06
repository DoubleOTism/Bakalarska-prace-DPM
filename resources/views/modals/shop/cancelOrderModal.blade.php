<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderLabel">Zrušit objednávku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Opravdu chcete zrušit objednávku?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
                <form action="{{ route('checkout.cancel') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sessionId" value="{{ $sessionId }}">
                    <button type="submit" class="btn btn-danger">Ano, zrušit</button>
                </form>
            </div>
        </div>
    </div>
</div>