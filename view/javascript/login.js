dom('#login-button').addEvent('click', function() {
  dom('#error').html('');
  ajax({
    url : '/login/post',
    type : 'POST',
    data : getFormData('form-tag'),
    success : ajaxSuccess
  });

  function ajaxSuccess(responseText) {
    if(responseText === ''){return;}
    console.log(responseText);
    if(json(responseText).exists('url')){window.location.href = json(responseText).json.url}


  }

});

dom(document).addEvent('keyup', function(e) {
  if(e.key === 'Enter') {
    dom('#login-button').elements[0].click();
  }
});

function getFormData(customTag) {
  let returnObj = {};
  dom('[' + customTag + ']').each(function(value, index, array) {
    let tagValue = dom(value).getAttr(customTag, true);
    returnObj[tagValue] = value.value;
  });
  return returnObj;
}
