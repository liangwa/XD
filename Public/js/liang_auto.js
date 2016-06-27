//新用户强制输入基本信息
$('#serverModal').on('hide.bs.modal', function () {
	location.reload();
})

$('#dcModal').on('hide.bs.modal', function () {
	location.reload();
})

$('#ddcModal').on('hide.bs.modal', function () {
	location.reload();
})

/*刷新实时生成对话框，暂不使用

$('#myModal').on('hide.bs.modal', function () {
  if(window.confirm('你确定要取消执行吗？')){
		//alert("确定");
		
		//刷新避免确认对话框生产多余的信息
		location.reload();
	}else{
		//alert("取消");
	}
})

$('#myModal').on('shown.bs.modal', function () {
	var label = document.createElement("LABEL");
	var labeltext = document.createTextNode("Your Server:");
	label.setAttribute("class", "col-sm-4 control-label");
	label.appendChild(labeltext);
	
	var div = document.createElement("div");
	div.setAttribute("id","num1");
	div.setAttribute("class","form-group");
	
	document.getElementById("c_form").appendChild(div);
	document.getElementById("num1").appendChild(label);

	//document.getElementById('c_xs').innerHTML = '修改后';
})
*/

$('#myModal').on('shown.bs.modal', function () {
	var xs = $("#xsconf").val();
	var dc = $("#dcconf").val();
	var ddc = $("#ddcconf").val();
	var vda = $("#vdatype").val();
	
	$("#c_xs").val(xs);
	$("#c_dc").val(dc);
	$("#c_ddc").val(ddc);
	$("#c_vda").val(vda);

	
	if(vda != "none") {
		$("#c_vda_os").val($("#vdaos").val());
		$("#c_vda_build").val($("#vdabuild").val());
		$("#c_vda_num").val($("#vdanum").val());
	}
	else {
		$("#c_vda_info").hide();
	}
	
	if(ddc == "auto") {
		$("#c_ddc_os").val($("#ddcos").val());
		$("#c_ddc_build").val($("#ddcbuild").val());
		$("#c_ddc_num").val($("#ddcnum").val());
	}
	else {
		$("#c_ddc_info").hide();
	}
	
	if(dc == "auto") {
		$("#c_dc_os").val($("#dcos").val());
		$("#c_dc_mname").val($("#dcname").val());
		$("#c_dc_name").val($("#dcname").val());
		$("#c_dc_ip").val($("#dcip").val());
	}
	else {
		$("#c_dc_info").hide();
	}

})

function ddc_noneed(){
	$("#vdatype").append("<option value='none'>****Not Need****</option>");
	$("#vdatype").val("none"); 
	$("#vdatype").attr("disabled","disabled");
	
	$("#ddc_info").hide();
	$("#vda_info").hide();
}

function ddc_auto(){
	$("#vdatype option").remove();
	$("#vdatype").removeAttr("disabled");
	
	$("#vdatype").append("<option value='full'>Full</option>");
	$("#vdatype").append("<option value='mini'>Mini</option>");
	$("#vdatype").append("<option value='none'>****Not Need****</option>");
	
	$("#vda_info").show();
	$("#ddc_info").show();
}

function ddc_other(){
	$("#vdatype option").remove();
	$("#vdatype").removeAttr("disabled");
	
	$("#vdatype").append("<option value='full'>Full</option>");
	$("#vdatype").append("<option value='mini'>Mini</option>");
	
	$("#vda_info").show();
	$("#ddc_info").hide();
}


function getDDC(obj) {
var ddctype = obj.value; 

	if (ddctype == "auto" ) {
		ddc_auto();
	}
	else if (ddctype == "none" ) {
		ddc_noneed();
	}
	else {
		ddc_other();
	}
	
}
