WYSIWYG = JAK.ClassMaker.makeClass({
	NAME : "WYSIWYG",
	VERSION : "1.0"
});

/**
 * @constructor
 * @overview Obstarává wysiwyg editor
 * @author aionet media, http://www.aionet.cz
 * @param {string} textAreaId Identifikátor textarea elementu, který budeme nahrazovat WYSIWYG editorem
 * @param {object} opt Konfigurační objekt
 */
WYSIWYG.prototype.$constructor = function(textAreaId, opt) {
	if (!textAreaId) return; 
	this.textAreaId = textAreaId;

	/* Výchozí hodnoty nastavení */
	this._opt = {
		autoresize_min_height: "500px",
		autoresize_max_height: "800px",
		menubar: false,
		selector: "textarea",
		language: "cs",
		content_css: "/css/tinymce.css",
		style_formats: [
			{ title: "Standardní odstavec", inline: "p" },
			{ title: "Perex", inline: "p", classes: "perex" },
			{ title: "Nadpis 2", inline: "h2" },
			{ title: "Nadpis 3", inline: "h3" },
			{ title: "Nadpis 3", inline: "h3" },
			{ title: "Nadpis 4", inline: "h4" },
			{ title: "Nadpis 5", inline: "h5" },
			{ title: "Nadpis 6", inline: "h6" }
		],
		plugins: "anchor autoresize code image link media table visualblocks",
		toolbar: "undo redo | styleselect | bold italic | link anchor | alignleft aligncenter alignright alignfull | bullist numlist | image media | table | code visualblocks"
	};

	/* Načtení předané konfigurace */
	for (var p in opt) {
		this._opt[p] = opt[p];
	}

	/* Editor */
	this.editor = new tinymce.Editor(this.textAreaId, this._opt, tinymce.EditorManager);
	this.editor.render();
};
