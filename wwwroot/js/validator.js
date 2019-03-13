Validator = JAK.ClassMaker.makeClass({
	NAME : "Validator",
	VERSION : 1.0
});

Validator.prototype.$constructor = function(formId, errCont, fields) {
	this._form = JAK.gel(formId);
	this._errCont = JAK.gel(errCont);

	this._isError;

	this._fields = fields;

	this._ec = [];
	this._ec.push(JAK.Events.addListener(this._form, "submit", this, "_validate"));
};

Validator.prototype._validate = function(e, elm) {
	JAK.Events.cancelDef(e);

	this._isError = 0;
	this._errCont.innerHTML = "<b>Chyba:</b><br />";
	JAK.DOM.addClass(this._errCont, "nodisplay");

	var field;
	for (var i=0; this._fields.length > i; i++) {
		field = document.getElementsByName(this._fields[i].fieldName)[0];
		if (field.type == "text" || field.type == "password") {
			if (!field.value) {
				JAK.DOM.addClass(field, "empty");
				this._writeError(this._fields[i].error);
			} else {
				JAK.DOM.removeClass(field, "empty");
			}
			
		}
	
	}

	if (!this._isError) {
		this._form.submit();
	}
};

Validator.prototype._writeError = function(err) {
	JAK.DOM.removeClass(this._errCont, "nodisplay");
	var message = JAK.mel("span", { innerHTML: (err + "<br />") });
	this._errCont.appendChild(message);
	this._isError = 1;
};
