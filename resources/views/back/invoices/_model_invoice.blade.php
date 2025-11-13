  <!-- Modal spécifique à cette facture -->
  <div class="modal fade" id="printChoiceModal{{ $invoice->id }}" tabindex="-1" role="dialog"
      aria-labelledby="printChoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content shadow">

              <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="printChoiceModalLabel{{ $invoice->id }}">
                      <i class="fas fa-print mr-2"></i> Choisir l'orientation
                      d'impression
                  </h5>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body py-5">
                  <p class="text-muted text-center mb-4">Sélectionnez le format :</p>
                  <div class="row justify-content-center align-items-stretch">
                      <div class="col-6 col-md-5">
                          <a href="{{ route('invoices.print', [$type.'s', $invoice->id]) }}?orientation=portrait"
                              target="_blank"
                              class="btn btn-outline-primary btn-lg btn-block h-100 d-flex flex-column align-items-center justify-content-center py-4">
                              <i class="fas fa-file-alt mb-3" style="font-size: 3em;"></i>
                              <span class="font-weight-bold">Portrait</span>
                          </a>
                      </div>
                      <div class="col-6 col-md-5">
                          <a href="{{ route('invoices.print', [$type.'s', $invoice->id]) }}?orientation=landscape"
                              target="_blank"
                              class="btn btn-outline-secondary btn-lg btn-block h-100 d-flex flex-column align-items-center justify-content-center py-4">
                              <i class="fas fa-file-alt mb-3" style="font-size: 3em; transform: rotate(90deg);"></i>
                              <span class="font-weight-bold">Paysage</span>
                          </a>
                      </div>
                  </div>
              </div>

              <div class="modal-footer border-0 bg-light">
                  <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Annuler</button>
              </div>

          </div>
      </div>
  </div>
