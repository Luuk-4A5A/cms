function domInit(selector) {
  if(selector instanceof HTMLElement){this.elements = [selector];}
  else if(selector === document) {this.elements = [document];}
  else {this.elements = document.querySelectorAll(selector);}
  this.selector = selector;

  this.html = function(content) {
    this.elements.forEach((value, index, array) => {
      value.innerHTML = content;
    });
    return this;
  };

  this.getAttr = function(attr, returnOne) {
    let tempArr = [];
     this.elements.forEach((value, index, array) => {
       var insertValue = value.getAttribute(attr);

      if(insertValue == 'true') {
        insertValue = true;
      } else if(insertValue == 'false') {
        insertValue = false;
      }
       tempArr.push(insertValue);
     });

    if(returnOne) {
      return tempArr[0];
    }

    return tempArr;
  };

  this.toggleAttr = function(attr, firstAttr, secondAttr) {
    if(this.getAttr(attr)[0] == firstAttr) {
      this.setAttr(attr, secondAttr);
    } else {
      this.setAttr(attr, firstAttr);
    }

    return this;
  }

  this.setAttr = function(attr, value) {
    this.elements.forEach(function(newvalue) {
      newvalue.setAttribute(attr, value);
    });
  }

  this.addEvent = function(tempEvent, callback) {
    this.elements.forEach((value, index, array) => {
      value.addEventListener(tempEvent, callback);
    });
    return this;
  };

  this.append = function(elementType, text = '') {
    let node = document.createElement(elementType);
    node.innerHTML = text;
    this.elements.forEach(function(value, index, array) {
      value.appendChild(node);
    });
    return this;
  }

  this.removeElement = function(elementType, index = 0) {
    let elementTypes = document.querySelectorAll(this.selector + ' ' + elementType);
    elementTypes.forEach(function(value) {
      console.log(value);
    });
  }

  this.each = function(callback) {
    for(let i = 0; i < this.elements.length; i++) {
      callback(this.elements[i], i, this.elements, this);
    }
  }

  return this;
}

function dom(selector) {
  return new domInit(selector);
}

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
 		var string = '';
 		objArr.forEach(function(value, index, array) {

 			string += ajaxObj.escape(value[0]) + '=' + ajaxObj.escape(value[1]) + '&';
 		});

 		string = string.slice(0, -1);
    return string;

 	}

  ajaxObj.escape = function(string) {
    return escape(string);
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

function jsonInit(jsonString) {
  this.json = (typeof jsonString === 'object') ? jsonString : JSON.parse(jsonString);

  this.each = function(callback) {
    for(let n in this.json) {
      callback(n, this.json[n], this.json);
    }
  }

  this.exists = function(index) {
    return this.json.hasOwnProperty(index);
  }

  return this;
}

function json(jsonString) {
  return new jsonInit(jsonString);
}
