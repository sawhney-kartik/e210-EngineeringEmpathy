        $(document).ready(function(){
            // Remove the "loading…" list entry
            $('ul#messages > li').remove();
            
            $('form').submit(function(){
                var form = $(this);
                var name =  form.find("input[name='name']").val();
                var content =  form.find("input[name='content']").val();
                
                // Only send a new message if it's not empty (also it's ok for the server we don't need to send senseless messages)
                if (name == '' || content == '')
                    return false;
                
                // Append a "pending" message (not yet confirmed from the server) as soon as the POST request is finished. The
                // text() method automatically escapes HTML so no one can harm the client.
                $.post(form.attr('action'), {'name': name, 'content': content}, function(data, status){
                    $('<li class="pending" />').text(content).prepend($('<small />').text(name)).appendTo('ul#messages');
                    $('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
                    form.find("input[name='content']").val('').focus();
                });
                return false;
            });
            
            // Poll-function that looks for new messages
            var poll_for_new_messages = function(){
                $.ajax({url: 'messages2.json', dataType: 'json', ifModified: true, timeout: 2000, success: function(messages, status){
                    // Skip all responses with unmodified data
                    if (!messages)
                        return;
                    
                    // Remove the pending messages from the list (they are replaced by the ones from the server later)
                    $('ul#messages > li.pending').remove();
                    
                    // Get the ID of the last inserted message or start with -1 (so the first message from the server with 0 will
                    // automatically be shown).
                    var last_message_id = $('ul#messages').data('last_message_id');
                    if (last_message_id == null)
                        last_message_id = -1;
                    
                    // Add a list entry for every incomming message, but only if we not already inserted it (hence the check for
                    // the newer ID than the last inserted message).
                    for(var i = 0; i < messages.length; i++)
                    {
                        var msg = messages[i];
                        if (msg.id > last_message_id)
                        {
                            var date = new Date(msg.time * 1000);
                            $('<li/>').text(msg.content).
                                prepend( $('<small />').text(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ' ' + msg.name) ).
                                appendTo('ul#messages');
                            $('ul#messages').data('last_message_id', msg.id);
                        }
                    }
                    
                    // Remove all but the last 50 messages in the list to prevent browser slowdown with extremely large lists
                    // and finally scroll down to the newes message.
                    $('ul#messages > li').slice(0, -50).remove();
                    $('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
                }});
            };
            
            // Kick of the poll function and repeat it every two seconds
            poll_for_new_messages();
            setInterval(poll_for_new_messages, 2000);
        });
