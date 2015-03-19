(function($) {

	$(window).load(function(){

		var _this    = $('#tm-chat-dialog'),
			$heading = $('.chat_box_heading'),
			$nick    = $('#chat-nick'),
			$email   = $('#chat-email'),
			$order   = $('#chat-order-id'),
			$subject = $('#chat-subject'),
			$message = $('#chat-message'),
			$chat_body = $('.chat_box_body'),
			$preloader = $('.preloader'),
			$chat_start_btn = $('.start_chat'),
			$chat_order_btn = $('.start_chat_order'),
			$order_form     = $('.tm-chat-accordion-item.first-item'),
			$default_form   = $('.tm-chat-accordion-item.second-item');

		_this.delay(1200).animate({bottom: -(_this.height()-$heading.height())}, 400);

		$chat_start_btn.on( 'click', function() {

			if ( _this.hasClass('online') || $order.hasClass('checked') ) {
				chat_connect();
			} else {

				var order_val = $.trim( $order.val() );
				if ( order_val.length >= 7 ) {
					$preloader.show();

					setTimeout(function() {
						check_order_id();
					}, 100);
				} else {
					if ( order_val.length != 0 ) {
						$order.addClass('LV_invalid_field');

						if ( $('.form-group-preloader .LV_validation_message').length == 0 ) {
							$order.after('<span class="LV_validation_message LV_invalid"><b>' + message_text.order_fail + '</b></span>');
						}

						disable_element( $chat_start_btn );
					} else {
						chat_connect();
					}
				}
			}

		});

		function chat_connect() {
			jQuery.ajax({
				type : 'post',
				dataType : 'json',
				url : ajaxurl,
				data : {
					action: 'cherry_tm_start_chat',
					chat_nick: $nick.val(),
					chat_email: $email.val(),
					chat_order: $order.val(),
					chat_subject: $subject.val(),
					chat_message: $message.val(),
					chat_product: $('#chat-product-type').val()
				},
				success: function(response) {
					if ( response.status == 'error' ) {
						$('.tm-chat-msg-error').show();
					} else {

						clear_all_fields();

						if ( response.type == 'chat' ) {
							var width  = 682,
								height = 523,
								url    = response.url,
								leftPosition,
								topPosition;

							leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
							topPosition  = (window.screen.height / 2) - ((height / 2) + 50);

							window.open(url, "Chat", "status=no,height=" + height + ",width=" + width + ",resizable=no,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");

							_this
								.removeClass('active')
								.animate({bottom: -( $chat_body.outerHeight() )}, 200);
							$('.chat_in').hide();
							$('.chat_out').show();

						} else {
							$('.tm-chat-msg-success').show();
						}
					}
				},
				async: false
			})
		};

		// Dialog toggle.
		$heading.on('click', function(event) {
			event.preventDefault();
			if ( $(this).hasClass('chat_out') ) {
				_this
					.addClass('active')
					.animate({bottom: 0}, 200, function() {
						if ( _this.hasClass('online') && _this.hasClass('order') && !$order.hasClass('checked') ) {
							$order.focus();
						} else {
							$nick.focus();
						}
					});
				$('.chat_out').hide();
				$('.chat_in').show();
			} else {
				if ( $(this).hasClass('chat_in') ) {
					$('.tm-chat-msg').hide();
					_this
						.removeClass('active')
						.animate( {bottom: -( $chat_body.outerHeight() )}, 200 );
					$('.chat_in').hide();
					$('.chat_out').show();
				}
			}
		});

		// Switch between forms (clear fields).
		$('.order-id-switch').on('click', function(event){
			event.preventDefault();
			_this.toggleClass('order');
			clear_all_fields();
		});

		$nick.on( 'keyup', function() {
			if ( true == check_is_empty( $(this) ) ) {

				if ( _this.hasClass('online') ) {
					if ( $email.hasClass('LV_valid_field') ) {
						enable_element($chat_start_btn);
					}
				} else {
					if ( $email.hasClass('LV_valid_field')
					&& $subject.hasClass('LV_valid_field')
					&& $message.hasClass('LV_valid_field')
					) {
						enable_element($chat_start_btn);
					}
				}

			} else {
				disable_element( $chat_start_btn );
			}
		});

		$email.on( 'keyup', function() {
			if ( true == check_is_empty( $(this) ) ) {

				if ( _this.hasClass('online') ) {
					if ( $nick.hasClass('LV_valid_field') ) {
						enable_element($chat_start_btn);
					}
				} else {
					if ( $nick.hasClass('LV_valid_field')
					&& $subject.hasClass('LV_valid_field')
					&& $message.hasClass('LV_valid_field')
					) {
						enable_element($chat_start_btn);
					}
				}

			} else {
				disable_element( $chat_start_btn );
			}
		});

		$subject.on( 'keyup', function() {
			if ( true == check_is_empty( $(this) ) ) {

				if ( $nick.hasClass('LV_valid_field')
				&& $email.hasClass('LV_valid_field')
				&& $message.hasClass('LV_valid_field')
				) {
					enable_element($chat_start_btn);
				}

			} else {
				disable_element( $chat_start_btn );
			}
		});

		$message.on( 'keyup', function() {
			if ( true == check_is_empty( $(this) ) ) {

				if ( $nick.hasClass('LV_valid_field')
				&& $email.hasClass('LV_valid_field')
				&& $subject.hasClass('LV_valid_field')
				) {
					enable_element( $chat_start_btn );
				}

			} else {
				disable_element( $chat_start_btn );
			}
		});

		// Live Validation.
		var _name = new LiveValidation('chat-nick', {
			validMessage: 'Ok',
			onlyOnBlur: true,
			onInvalid: function() {
				this.insertMessage( this.createMessageSpan() );
				this.addFieldClass();
				disable_element( $chat_start_btn );
			}
		}),
		_email = new LiveValidation('chat-email', {
			validMessage: 'Ok',
			onlyOnBlur: true,
			onInvalid: function() {
				this.insertMessage( this.createMessageSpan() );
				this.addFieldClass();
				disable_element( $chat_start_btn );
			}
		});

		_name.add( Validate.Format, {
			pattern: /^[a-zA-Z][0-9a-zA-Z]{1,50}$/,
			failureMessage: message_text.name_fail
		});
		_email.add( Validate.Email, {
			failureMessage: message_text.email_fail
		});

		if ( _this.hasClass('online') ) {

			var _order = new LiveValidation( 'chat-order-id', {
				validMessage: 'Ok',
				onlyOnBlur: false,
				onValid: function() {
					var order_val = $.trim( $order.val() );
					if ( order_val.length != 0 ) {
						this.removeMessage();
						this.removeFieldClass();
						enable_element( $chat_order_btn );
					} else {
						disable_element( $chat_order_btn );
					}
				},
				onInvalid: function() {
					this.insertMessage( this.createMessageSpan() );
					this.addFieldClass();
					disable_element( $chat_order_btn );
				}
			} );
			_order.add( Validate.Format, {
				failureMessage: message_text.order_fail,
				pattern: /^[0-9a-zA-Z]{6,50}$/
			} );

		} else {

			$order.on( 'keyup', function() {
				if ( true == check_is_empty( $(this) ) ) {

					if ( $nick.hasClass('LV_valid_field')
					&& $email.hasClass('LV_valid_field')
					&& $subject.hasClass('LV_valid_field')
					&& $message.hasClass('LV_valid_field')
					) {
						enable_element( $chat_start_btn );
					}

				} else {
					disable_element( $chat_start_btn );
				}
			});

			var _subject = new LiveValidation('chat-subject', {
				validMessage: 'Ok',
				onlyOnBlur: true,
				onInvalid: function() {
					this.insertMessage( this.createMessageSpan() );
					this.addFieldClass();
					disable_element($chat_start_btn);
				}
			}),
			_message = new LiveValidation('chat-message', {
				validMessage: 'Ok',
				onlyOnBlur: true,
				onInvalid: function() {
					this.insertMessage( this.createMessageSpan() );
					this.addFieldClass();
					disable_element($chat_start_btn);
				}
			});

			_subject.add( Validate.Length, {
				tooShortMessage: message_text.subject_fail,
				minimum: 1
			});
			_message.add( Validate.Length, {
				tooShortMessage: message_text.message_fail,
				minimum: 1
			});

		}

		$('body').on('change','#chat-product-type',function () {
			if ( '' !== $(this).val()) {
				$(this).after('<span class="LV_validation_message LV_valid LV_valid_select"><i></i></span>');
				enable_element($chat_start_btn);
			} else {
				$('.LV_valid_select').remove();
				disable_element($chat_start_btn);
			}
		} );

		// Send request for `Order ID` checking.
		$chat_order_btn.on( 'click', function() {
			$preloader.show();

			setTimeout(function() {
				check_order_id();
			}, 100);
		} );
		function check_order_id() {
			jQuery.ajax({
				type : 'post',
				dataType : 'json',
				url : ajaxurl,
				data : {
					action: 'check_order',
					chat_order: $order.val()
				},
				beforeSend: function() {
					$('#chat-product-type').remove();
					$('.form-group-preloader .LV_validation_message').remove()
					disable_element( $chat_order_btn );
				},
				success: function( response ) {
					if ( ( 'success' == response.status ) ) {

						$order.addClass('checked');

						if ( 1 == response.count ) {

							$('#pr-type-group')
								.append( '<input type="hidden" name="chat-product-type" id="chat-product-type" value="">' );

							data = parse_item( response.order_data[0] );
							$('#chat-product-type').val( data[1] );

							// Go to chat.
							chat_connect();

						} else if ( response.count > 1 ) {

							$('#pr-type-group')
								.append( '<select name="chat-product-type" id="chat-product-type" required tabindex="2"><option value="">' + message_text.select_default + '</option></select>' )
								.removeClass('hidden');

							$.each( response.order_data, function( index, value ) {
								data = parse_item( value );

								$('#chat-product-type')
									.append( $('<option></option>')
									.attr( 'value', data[1] )
									.text( data[0] ) );
							});

							$order_form.
								find( $chat_order_btn )
								.hide();
							$order_form.
								find( $chat_start_btn)
								.show();

						}

						if ( _this.hasClass('online') ) {
							$order
								.removeClass('LV_invalid_field')
								.after('<span class="LV_validation_message LV_valid"><i></i></span>');
						} else {
							$order.removeClass('LV_invalid_field');
						}

					} else {
						if ( _this.hasClass('online') ) {
							$order
								.addClass('LV_invalid_field')
								.after('<span class="LV_validation_message LV_invalid"><b>' + message_text.order_bad + '</b><i></i></span>');
							disable_element( $chat_order_btn );
						} else {
							$order
								.addClass('LV_invalid_field')
								.after('<span class="LV_validation_message LV_invalid"><b>' + message_text.order_bad + '</b></span>');
							disable_element( $chat_start_btn );
						}
					}

					$preloader.hide();
				},
				async: false
			})
		}

		function clear_all_fields() {
			$nick.val('').removeClass('LV_valid_field LV_invalid_field');
			$email.val('').removeClass('LV_valid_field LV_invalid_field');
			$order.val('').removeClass('LV_valid_field LV_invalid_field checked');
			$subject.val('').removeClass('LV_valid_field LV_invalid_field');
			$message.val('').removeClass('LV_valid_field LV_invalid_field');
			$(_this).find('.LV_validation_message').remove();
			$('#pr-type-group').addClass('hidden');
			disable_element($chat_start_btn);
			disable_element($chat_order_btn);
			if ( _this.hasClass('order') ) {
				$chat_start_btn.hide();
				$chat_order_btn.show();
			} else {
				$chat_start_btn.show();
				$chat_order_btn.hide();
			}
			$order.removeClass('checked');
		}

	}); // window.load

	function parse_item(item) {
		var option_text  = '',
			option_value = '';

		if ('true' != item.is_template) {
			return false;
		}
		option_text  = item.type_name + ' #' + item.template;
		option_value = item.type + ' #' + item.template;

		return [option_text, option_value];
	}

	function disable_element(el) {
		el.prop('disabled', true)
			.addClass('disabled')
			.removeClass('enabled');
	}

	function enable_element(el) {
		el.prop('disabled', false)
			.addClass('enabled')
			.removeClass('disabled');
	}

	function check_is_empty(el) {
		var value = el.val();
		if ( value.length != 0 ) {
			return true;
		}
		return false;
	}

})(jQuery);