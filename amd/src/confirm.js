
define(['jquery', 'core/modal_factory', 'core/str', 'core/modal_events', 'core/ajax', 'core/notification'],
    function($, ModalFactory, String, ModalEvents, Ajax, Notification) {
        var trigger = $(".local_message_delete_button");
        ModalFactory.create({
            type: ModalFactory.types.SAVE_CANCEL,
            title: String.get_string('delete:title', 'local_message'),
            body: String.get_string('delete:body', 'local_message'),
            preShowCallback: function(triggerElement, modal) {
                triggerElement = $(triggerElement);
                let message_id = triggerElement[0].classList[0];
                message_id = message_id.substr(message_id.lastIndexOf('messageid') + 'messageid'.length);

                modal.params = {'messageid' : message_id};
                modal.setSaveButtonText(String.get_string('delete:button','local_message'));
            },
            large: true
        } , trigger)
            .done(function(modal) { 
                
                modal.getRoot().on(ModalEvents.save, function(e) {
                    
                    e.preventDefault();

                    let footer = Y.one('.modal-footer');
                    footer.setContent('Deleting...');
                    let spinner = M.util.add_spinner(Y, footer);
                    spinner.show();
                    let request = {
                        methodname: 'local_message_delete_message',
                        args: modal.params,
                    };
                    Ajax.call([request])[0].done(function(data) {
                        if (data === true) {
                            // Redirect to manage page.
                            Notification.addNotification({
                                message: String.get_string('delete:success', 'local_message'),
                                type: 'success',
                            });
                            window.location.reload();
                        } else {
                            Notification.addNotification({
                                message: String.get_string('delete:failed', 'local_message'),
                                type: 'error',
                            });
                        }
                    }).fail(Notification.exception);

                });
            });
});