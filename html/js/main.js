const api='/api/v1';
const appkey='en0O4yYREn879va4'
let soTimeout;
$(document).ready(()=>{
	$('#getComplex').on('click',function(){
		var obj=$(this);
		$.ajax({
			url: api+"/getComplex",
			method: 'get',
			data: 'appkey='+appkey+'&id=1'
		}).then(msg=>{
			$('#getComplexResult').html(JSON.stringify(msg).replaceAll(',','<br>'));
		})
		return false;
	});
	$('#saveClient').on('click',function(){
		var obj=$(this);
		$.ajax({
			url: api+"/saveClient",
			method: 'post',
			data: $('#saveClientForm').serialize()
		}).then(msg=>{
			$('#saveClientResult').html(JSON.stringify(msg).replaceAll(',','<br>'));
		})
		return false;
	});
	$('#getMenu').on('click',function(){
		var obj=$(this);
		$.ajax({
			url: api+"/getMenu",
			method: 'post',
			data: $('#getMenuForm').serialize()
		}).then(msg=>{
			console.log(msg)
			var iday
			var x=1
			let text=msg.Data.list.map(function(items){
				iday=''
				iday=iday+'<tr><th colspan="9">День '+x+'</th></tr>'
				iday=iday+'<tr><td colspan="9">Завтрак</td></tr>'
				iday=iday+items.zavtrak.map(function(item){
					return `<tr>
							<td>${item.id}</td>
							<td>${item.name}</td>
							<td>${item.measure} ${item.units}</td>
							<td>${item.iccal}</td>
							<td>${item.iprot}</td>
							<td>${item.ifat}</td>
							<td>${item.icbht}</td>
							<td>${item.dish}</td>
							<td>${item.allergy}</td>
						</tr>
					`
				}).join('')
				iday=iday+'<tr><td colspan="9">Обед</td></tr>'
				iday=iday+items.obed.map(function(item){
					return `<tr>
							<td>${item.id}</td>
							<td>${item.name}</td>
							<td>${item.measure} ${item.units}</td>
							<td>${item.iccal}</td>
							<td>${item.iprot}</td>
							<td>${item.ifat}</td>
							<td>${item.icbht}</td>
							<td>${item.dish}</td>
							<td>${item.allergy}</td>
						</tr>
					`
				}).join('')
				iday=iday+'<tr><td colspan="9">Полдник</td></tr>'
				iday=iday+items.poldnik.map(function(item){
					return `<tr>
							<td>${item.id}</td>
							<td>${item.name}</td>
							<td>${item.measure} ${item.units}</td>
							<td>${item.iccal}</td>
							<td>${item.iprot}</td>
							<td>${item.ifat}</td>
							<td>${item.icbht}</td>
							<td>${item.dish}</td>
							<td>${item.allergy}</td>
						</tr>
					`
				}).join('')
				iday=iday+'<tr><td colspan="9">Ужин</td></tr>'
				iday=iday+items.ujin.map(function(item){
					return `<tr>
							<td>${item.id}</td>
							<td>${item.name}</td>
							<td>${item.measure} ${item.units}</td>
							<td>${item.iccal}</td>
							<td>${item.iprot}</td>
							<td>${item.ifat}</td>
							<td>${item.icbht}</td>
							<td>${item.dish}</td>
							<td>${item.allergy}</td>
						</tr>
					`
				}).join('')
				x++
				return iday
			}).join('')
			$('#getMenuResult').html('<table class="table">'+text+'</table>')
		});
		return false;
	});
	$('#loginBut').on('click',function(){
		const login=$('#login').val();
		const password=sha256($('#password').val());
		$.ajax({
		  url: api+"/login",
		  method: 'get',
		  headers: {
			"Authorization":"Basic "+btoa(login + ":" + password)
		  }
		}).then(msg=>{
			$('#loginResult').html(JSON.stringify(msg).replaceAll(',','<br>'));
		}).fail(msg=>{
			$('#loginResult').html(JSON.stringify(msg).replaceAll(',','<br>'));
		})
		return false;
	});
})
