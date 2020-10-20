<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">

	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-smsprostor" data-toggle="tooltip" title="<?=$button_save?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?=$button_cancel?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php } ?>
			</ul>
		</div>
	</div>

	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?=$error_warning?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-6">
						<h3 class="panel-title"><i class="fa fa-pencil"></i> <?=$text_description?></h3>
					</div>
					<div class="col-sm-6 text-right">
						<?=$entry_support?>&nbsp;&nbsp;
						<?=$entry_balance?> <b><?=(isset($balance)? $balance: $entry_undefined_balance)?></b>&nbsp;&nbsp;<?=$entry_addbalance?>&nbsp;&nbsp;<?=$entry_gosite?>
					</div>
				</div>
			</div>
			<div class="panel-body">

				<form action="<?=$action?>" method="post" enctype="multipart/form-data" id="form-smsprostor" class="form-horizontal">

					<ul class="nav nav-tabs" style="margin-bottom: 0px">
						<li class="active"><a href="#tab-notice" data-toggle="tab"><?=$tab_notice?></a></li>
						<li><a href="#tab-message" data-toggle="tab"><?=$tab_message?></a></li>
						<li><a href="#tab-gate" data-toggle="tab"><?=$tab_gate?></a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab-notice">
							<div class="form-group">

								<label class="col-sm-2 control-label" for="input-enabled">
									<span data-toggle="tooltip" data-original-title="<?=$help_enabled?>"><?=$entry_enabled?></span>
								</label>

								<div class="col-sm-10">
									<select name="smsprostor-enabled" class="form-control">
										<?php if ((isset($data['smsprostor-enabled'])) && ($data['smsprostor-enabled'])) { ?>
										<option value="1" selected="selected"><?= $text_enable?></option>
										<option value="0"><?=$text_disable?></option>
										<?php } else { ?>
										<option value="1"><?=$text_enable?></option>
										<option value="0" selected="selected"><?=$text_disable?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">

								<label class="col-sm-2 control-label" for="smsprostor-status1">
									<span data-toggle="tooltip" data-original-title="Статус заказа покупателя">Статус заказа покупателя</span>
								</label>

								<div class="col-sm-10">
									<select name="smsprostor-status1" class="form-control">
										<?php foreach ($statuses as $status) : ?>
										<option value="<?=$status["order_status_id"]?>"  <?php echo ((isset($data['smsprostor-status1'])) && ($data['smsprostor-status1'] == $status["order_status_id"]))? 'selected="selected"': "";?>><?=$status["name"]?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-message-customer">
									<span data-toggle="tooltip" data-original-title="<?=$help_message_customer?>">СМС покупателю</span>
								</label>
								<div class="col-sm-10">

									<input type="checkbox" name="smsprostor-send-customer" <?=isset($data['smsprostor-send-customer'])?'checked':''?> /> <?=$entry_send_customer?>

									<div class="btn-group-xs btn-group" role="group" style="float: right">
										<button class="btn btn-default btni" type="button" data-insert="{orderid}" data-target="input-message-customer"><?=$button_orderid?></button>
										<button class="btn btn-default btni" type="button" data-insert="{storename}" data-target="input-message-customer"><?=$button_storename?></button>
										<button class="btn btn-default btni" type="button" data-insert="{firstname}" data-target="input-message-customer"><?=$button_firstname?></button>
										<button class="btn btn-default btni" type="button" data-insert="{lastname}" data-target="input-message-customer"><?=$button_lastname?></button>
									</div>
									<br><br>
									<textarea name="smsprostor-message-customer" rows="5" placeholder="<?=$entry_message_customer?>" id="input-message-customer" class="form-control"><?php echo isset($data['smsprostor-message-customer'])? $data['smsprostor-message-customer']: 'Уважаемый {firstname} {lastname}! Спасибо за покупки в {storename}. Ваш номер заказа {orderid}.'; ?></textarea>

								</div>
							</div>

							<div class="form-group">

							<label class="col-sm-2 control-label" for="smsprostor-status2">
								<span data-toggle="tooltip" data-original-title="Статус заказа администратора">Статус заказа администратора</span>
							</label>

							<div class="col-sm-10">
								<select name="smsprostor-status2" class="form-control">
									<?php foreach ($statuses as $status) : ?>
									<option value="<?=$status["order_status_id"]?>"  <?php echo ((isset($data['smsprostor-status2'])) && ($data['smsprostor-status2'] == $status["order_status_id"]))? 'selected="selected"': "";?>><?=$status["name"]?></option>
									<?php endforeach; ?>
								</select>
							</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-message-admin">
									<span data-toggle="tooltip" data-original-title="<?=$help_message_admin?>">СМС администратору</span>
								</label>
								<div class="col-sm-10">

									<input type="checkbox" name="smsprostor-send-admin" <?=isset($data['smsprostor-send-admin'])?'checked':''?> /> &nbsp;&nbsp; <?=$entry_send_admin?>

									<div class="btn-group-xs btn-group" role="group" style="float: right">
										<button class="btn btn-default btni" type="button" data-insert="{orderid}" data-target="input-message-admin"><?=$button_orderid?></button>
										<button class="btn btn-default btni" type="button" data-insert="{storename}" data-target="input-message-admin"><?=$button_storename?></button>
										<button class="btn btn-default btni" type="button" data-insert="{firstname}" data-target="input-message-admin"><?=$button_firstname?></button>
										<button class="btn btn-default btni" type="button" data-insert="{lastname}" data-target="input-message-admin"><?=$button_lastname?></button>
										<button class="btn btn-default btni" type="button" data-insert="{total}" data-target="input-message-admin"><?=$button_total?></button>
										<button class="btn btn-default btni" type="button" data-insert="{email}" data-target="input-message-admin"><?=$button_email?></button>
									</div>
									<br><br>
									<textarea name="smsprostor-message-admin" rows="5" placeholder="<?=$entry_message_admin?>" id="input-message-admin" class="form-control"><?php echo isset($data['smsprostor-message-admin'])? $data['smsprostor-message-admin']: 'Сделан заказ №{orderid} в {storename} от имени {firstname} {lastname} на сумму {total}.'; ?></textarea>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-message">

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-responders">
										<span data-toggle="tooltip" data-original-title="<?=$help_responders?>">
											<?=$entry_responders?>
										</span>
								</label>

								<div class="col-sm-10">
									<select name="smsprostor-responders" id="input-responders" multiple="multiple" class="form-control" style="width: 100%">

										<option value="*">Все пользователи</option>

										<?php foreach($customer_groups as $customer_group): ?>
										<option value="@<?=$customer_group['customer_group_id']?>">Группа <?=$customer_group['name']?></option>
										<?php endforeach;?>

										<?php foreach($customers as $customer): ?>
										<option value="<?=$customer['telephone']?>"><?=$customer['firstname'].' '.$customer['lastname']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-message-responder">
									<span data-toggle="tooltip" data-original-title="<?=$help_message_responder?>"><?=$entry_message_responder?></span>
								</label>
								<div class="col-sm-10">
									<textarea rows="5" id="input-message-responder" class="form-control"></textarea>
								</div>
							</div>

							<button class="btn btn-primary pull-right" id="sending">Отправить</button>

						</div>
						<div class="tab-pane" id="tab-gate">

							<div class="form-group">

								<label class="col-sm-2 control-label" for="input-login">
										<span data-toggle="tooltip" data-original-title="<?=$help_login?>">
											<?=$entry_login?>
										</span>
								</label>

								<div class="col-sm-10">
									<input name="smsprostor-login" type="text" placeholder="<?=$entry_login?>" id="input-login" class="form-control" value="<?php echo isset($data['smsprostor-login'])? $data['smsprostor-login']: ''; ?>">
								</div>
							</div>

							<div class="form-group">

								<label class="col-sm-2 control-label" for="input-password">
										<span data-toggle="tooltip" data-original-title="<?=$help_password?>">
											<?=$entry_password?>
										</span>
								</label>

								<div class="col-sm-10">
									<input name="smsprostor-password" type="text" placeholder="<?=$entry_password?>" id="input-password" class="form-control" value="<?php echo isset($data['smsprostor-password'])? $data['smsprostor-password']: ''; ?>">
								</div>
							</div>

							<div class="form-group">

								<label class="col-sm-2 control-label" for="input-sender">
										<span data-toggle="tooltip" data-original-title="<?=$help_sender?>">
											<?=$entry_sender?>
										</span>
								</label>

								<div class="col-sm-10">
									<select name="smsprostor-sender" id="input-sender" class="form-control" <?=(empty($senders)? 'disabled': '')?>>
									<?php if (isset($senders)): ?>
										<?php foreach ($senders as $id => $sender): ?>
										<option value="<?=$sender?>" <?=( ((isset($data['smsprostor-sender'])) && ($sender == $data['smsprostor-sender']) )? 'selected': '')?>><?=$sender?></option>
										<?php endforeach; ?>
									<?php endif; ?>
									</select>
								</div>
							</div>

							<div class="form-group">

								<label class="col-sm-2 control-label" for="input-phone">
										<span data-toggle="tooltip" data-original-title="<?=$help_phone?>">
											<?=$entry_phone?>
										</span>
								</label>

								<div class="col-sm-10">
									<input name="smsprostor-phone" type="text" placeholder="<?=$entry_phone?>" id="input-phone" class="form-control digitOnly" value="<?php echo isset($data['smsprostor-phone'])? $data['smsprostor-phone']: '';?>" maxlength="11">
								</div>
							</div>
						</div>
					</div>

				</form>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

    function delayedLoop(collection, delay, callback, context) {
        context = context || null;

        var i = 0,
            nextInteration = function() {
                if (i === collection.length) {
                    return;
                }

                callback.call(context, collection[i], i);
                i++;
                setTimeout(nextInteration, delay);
            };

        nextInteration();
    }

$( document ).ready(function() {

    $('#input-responders').select2({
        tags: true,
        tokenSeparators: [',']
    });

    $.fn.insertAtCaret = function(myValue) {
        return this.each(function() {
            var me = this;
            if (document.selection) { // IE
                me.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                me.focus();
            } else if (me.selectionStart || me.selectionStart == '0') { // Real browsers
                var startPos = me.selectionStart, endPos = me.selectionEnd, scrollTop = me.scrollTop;
                me.value = me.value.substring(0, startPos) + myValue + me.value.substring(endPos, me.value.length);
                me.focus();
                me.selectionStart = startPos + myValue.length;
                me.selectionEnd = startPos + myValue.length;
                me.scrollTop = scrollTop;
            } else {
                me.value += myValue;
                me.focus();
            }
        });
    };

	$('.btni').click(function(){
		var target = $(this).data('target');
		var text = $(this).data('insert');
		$('#'+target).insertAtCaret(text);
	});

    $("#sending").click(function(event) {
        event.preventDefault();
        var responders = [];
        $.each($('#input-responders').select2('data'), function(index, value) {
            responders.push(value.id);
        });
        var data = $.ajax({
            type: 'POST',
            url: '<?=$action_process?>',
            data: {
				data: JSON.stringify(responders)
			}
        }).done(function( data ) {
            $('#sending').prop('disabled', true)
            var phones = $.parseJSON(data)
            $('#sending').html('Отправка сообщения 1...');
            delayedLoop(phones, 500, function(phone, index) {
                $('#sending').html('Отправка сообщения '+(index+1)+'...');
                $.ajax({
                    type: 'POST',
                    url: '<?=$action_send?>',
                    data: {
                        phone: phone,
						message: $('#input-message-responder').val()
                    },
					async: false
                }).done(function(){
                    if (phones.length == (index+1)) {
                        $('#sending').prop('disabled', false);
                        $('#sending').html('Отправить');
                        alert('Сообщения отправлены');
                    }
				});
            });
        });
    });
});

</script>
<?php echo $footer; ?>