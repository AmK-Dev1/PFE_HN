<div class="modal" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModal" aria-hidden="true" >
    <div class="modal-dialog    modal-warning modal-dialog-centered  " role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-notification">{{trans_choice('labels.models.order',1)}}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="py-1 text-center">
                    <i class="fa fa-exclamation-triangle ni-2x"></i>
                    <h4 class="heading mt-4">{{__('messages.static.driver_not_found')}}</h4>

                </div>

            </div>

            <div class="modal-footer mt-0">
                <a class="btn btn-primary" id="alert_link" href="">{{__('messages.static.details')}}</a>
                <button type="button" class="btn btn-outline-danger  ml-auto" data-dismiss="modal">{{__('messages.static.cancel')}}</button>
            </div>
        </div>
    </div>
</div>
