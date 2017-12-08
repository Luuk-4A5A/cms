/**
 * Ajax function
 */
 function ajax(options) {
 	let httpTypes = ["GET", "POST"];
 	let ajaxObj = {};
 	ajaxObj.type = (Boolean(options.type) && httpTypes.indexOf(options.type) !== -1) ? options.type : 'GET';
 	ajaxObj.url = (Boolean(options.url)) ? options.url : '/';

 	ajaxObj.processData = (Boolean(options.processData)) ? options.processData : true;
 	ajaxObj.data = (Boolean(options.data)) ? options.data : {};
 	ajaxObj.success = (Boolean(options.success)) ? options.success : function(responseText){console.log(responseText)};

 	ajaxObj.serialize = function(obj) {
 		let objArr = Object.entries(obj);
 		var string = ''
 		objArr.forEach(function(value, index, array) {
 			string += value[0] + '=' + value[1] + '&';
 		});

 		string = string.slice(0, -1);
 		return string;
 	}

 	if(ajaxObj.processData == true) {
 		ajaxObj.httpString = ajaxObj.serialize(ajaxObj.data);
 	}

 	if(ajaxObj.type === 'GET') {
 		ajaxObj.url = ajaxObj.url += '/?' + ajaxObj.httpString;
 	}

 	let ajax = new XMLHttpRequest();
 	ajax.open(ajaxObj.type, ajaxObj.url, true);
   ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 	ajax.onreadystatechange = function() {
 		if(ajax.readyState == 4 && ajax.status == 200) {
 			ajaxObj.success(ajax.responseText);
 		}
 	}

 	ajax.send(ajaxObj.httpString);
 }
