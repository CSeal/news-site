function checkForm(event){
	var bad = "", emailPattern = /^[a-z0-9_][a-z0-9\.-]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]{2,3}$/i, passwordPattern = /^([a-z0-9_-]|\!|\?){6,}$/i, forma = $(event.target),
	checkQuotes = function(elemVal){ 
		var black_list = ["\"", "'", "`", "&quot;", "&apos;"];
		for(var key in black_list)
		{
			
			if(elemVal.indexOf(black_list[key]) !== -1) return true;
		}
		return false;
	}, equilCheck = function(fEqual){ return forma.find("[name = "+fEqual+"]").val()};
	forma.find('[data-type]').each(function(i, elem){
		elem = $(elem);
		var tEmpty = elem.attr("data-tEmpty"), type = elem.attr("data-type"), tType = elem.attr("data-tType"), minLen = elem.attr("data-minLen"),
		tMinLen = elem.attr("data-tMinLen"), maxLen = elem.attr("data-maxLen"), tMaxLen = elem.attr("data-tMaxLen"), fEqual = elem.attr("data-fEqual"), tEqual = elem.attr("data-tEqual"), elemVal = elem.val();
		if( tEmpty && (elemVal.length == 0)) bad += tEmpty + "\n";
		else{
			console.log(type + "  " + elemVal + "  " + passwordPattern.test(elemVal) + "\n");
			if(minLen && (elemVal.length < minLen))  bad += tMinLen + "\n";
			if(maxLen && (elemVal.length > maxLen))  bad += tMaxLen + "\n";
			if((type === "email" && emailPattern.test(elemVal) === false) ||
			(type === "password" && passwordPattern.test(elemVal) === false) ||
			((type === "name" || type === "login") && checkQuotes(elemVal))) bad +=  tType + "\n";
			if(fEqual && equilCheck(fEqual) !== elemVal) bad += tEqual + "\n";
		};
	});
	if(bad === ""){
		if(forma.attr('data-tConfirm')) confirm(forma.attr('data-tConfirm'));
		bad = emailPattern = passwordPattern = checkQuotes  = equilCheck = forma = null;
		return true;
	};
	alert(bad);
	bad = emailPattern = passwordPattern = checkQuotes  = equilCheck = forma = null;
	return false;
	
};





/*Проверка формы на валидность. Зависит от jsvalidator_class.php qwertyz.*
function checkForm(form){
	var elem = $(form).find("[data-type]");// находим все эдементы формы с указаным атрибутом data-type
	console.log("fsdfsd");
	var bad = "";// Переменная для хранения ошибок
	var emailPattern = /^[a-z0-9_][a-z0-9\.-]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]{2,3}$/i, passwordPattern = /^[a-z0-9_-]|!|\?$/gi;
	for(var i = 0; i < elem.length; i++){
		var jObj = $(elem[i]);
		console.log(jObj.val());
		bad += new ElemAttrForValidate(jObj, jObj.val(), jObj.attr("data-type"), jObj.attr("data-tType"), jObj.attr("data-minLen"), jObj.attr("data-tMinLen"), jObj.attr("data-maxLen"), jObj.attr("data-tMaxLen"), jObj.attr("data-tEmpty"), jObj.attr("data-fEqual"), jObj.attr("data-tEqual")).startChecked(passwordPattern, emailPattern); 
	}
	
	if(bad === ""){
		var tConfirm = $(elem).attr("data-tConfirm");
		if(tConfirm) confirm(tConfirm);
		elem = bad = emailPattern = passwordPattern = null;
		return true;
	} ;
		alert(bad);
		elem = bad = emailPattern = passwordPattern = null;
		return false;

};
function ElemAttrForValidate(elem, val, type, tType, minLen, tMinLen, maxLen, tMaxLen, tEmpty , fEqual, tEqual){ 
	this.elem = elem;
	this.val = val;
	this.type = type,
	this.tType = tType,
	this.minLen = minLen,
	this.tMinLen = tMinLen,
	this.maxLen = maxLen,
	this.tMaxLen = tMaxLen,
	this.tEmpty = tEmpty,
	this.fEqual = fEqual,
	this.tEqual = tEqual
};
ElemAttrForValidate.prototype.equilCheck = function(){
	return this.elem.parent().find("[name = "+this.fEqual+"]").val() === this.val;
};
ElemAttrForValidate.prototype.checkQuotes = function(){
	var black_list = ["\"", "'", "`", "&quot;", "&apos;"];
		for(var key in black_list)
		{
			
			if(this.val.indexOf(black_list[key]) !== -1) return true;
		}
		return false;
	
};
ElemAttrForValidate.prototype.startChecked = function(passwordPattern, emailPattern){
	var bad = "";
	if(this.tEmpty && this.val.length === 0){
		bad +=  this.tEmpty +" "+this.type+ "\n";
	}else{
		
		if(this.minLen && this.val.length < this.minLen) bad += this.tMinLen + "\n";
		if(this.maxLen && this.val.length > this.maxLen) bad += this.tMaxLen + "\n";
		if((this.type === "email" && !emailPattern.test(this.val)) ||
	   (this.type === "password" && !passwordPattern.test(this.val)) ||
	   ((this.type === "name" || this.type === "login") && this.checkQuotes())){
		 console.log(this.val);
		 bad+=  this.tType + "\n";  
	   };
		if(this.fEqual && !this.equilCheck()) bad +=  this.tEqual + "\n";
	};	
	return bad;	
};
*/
