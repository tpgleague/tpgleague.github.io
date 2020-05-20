

var colourscheme = ['#674ec6','#c64e98','#c64e4e','#a3c64e','#c6bc4e','#4ec697','#c64e68'];

function showChart(rowArray,oHead,oOS) {

	window.graphWin = window.open('','_blank');
	if( !window.graphWin ) { window.graphWin = window; } //try to cope with popup blockers by using the existing window
	window.graphWin.document.write('<html><head><title>Speed charts<\/title><style type=\"text\/css\">\nbody { background-color: #fff; color: #000; font-family: Arial, sans-serif; }\n'+
		'table.mwjgraph { background-color: #eee; border: 2px solid #000; margin-top: 1.5em; }\n<\/style><\/head><body>'+
		'<h1>'+oHead+' - speed charts<\/h1>');

	var headingRow = document.getElementById(tableSorts[0]).getElementsByTagName('tr')[0];

	for( var t = 1; t < headingRow.getElementsByTagName('th').length; t++ ) {

		var grTit = headingRow.getElementsByTagName('th')[t].firstChild.nodeValue;

		window.sortingBy = t;
		rowArray.sort(rowSort);

		for( var i = 0, nms = '', vals = '', tmpvl = 0; rowArray[i]; i++ ) {
			tmpvl = rowArray[i].getElementsByTagName('td')[t-1].firstChild.nodeValue;
			nms += ( nms ? ',' : 'g.setXAxis(' ) + '\'' + rowArray[i].getElementsByTagName('th')[0].firstChild.nodeValue +
				( oOS ? ( ' - ' + rowArray[i].parentNode.parentNode.getElementsByTagName('caption')[0].firstChild.nodeValue.replace(/speed[\w\W]*$/,'') ) : '' ) +
				((tmpvl=='-')?'<br><span style=\"color:red;\">ERROR<\\\/span>':'') + '\'';
			vals += ( vals ? ',' : 'g.addDataSet(\''+colourscheme[t-1]+'\',\'seconds\',[' ) + ( ( tmpvl == '-' ) ? 0 : tmpvl );
		}
		if( !vals ) {
			window.graphWin.document.write(
				'<p>All relevant browsers are excluded. You will have to re-enable those browsers to show their graphs.</p>'+
				'<\/body><\/html>'
			);
			window.graphWin.document.close();
			return;
		}
		nms += ');\n';
		vals += ']);\n';

		eval(
			'var g = new MWJ_graph(Math.round(600*(screen.availWidth\/1024)),'+(rowArray.length*20)+',MWJ_bar,false,true);\n'+
			"g.setTitles(\'"+grTit+"\',\'\',\'Time in seconds\');\n"+vals+nms+
			'g.buildGraph();\n'
		);

	}

	window.graphWin.document.write(
		'<\/body><\/html>'
	);
	window.graphWin.document.close();

}

function rowSort(a,b) {
	if( sortingBy ) {
		//numeric sort
		var c = parseFloat(a.getElementsByTagName('td')[sortingBy-1].firstChild.nodeValue);
		var d = parseFloat(b.getElementsByTagName('td')[sortingBy-1].firstChild.nodeValue);
		if( isNaN(c) ) { c = 10000000; } if( isNaN(d) ) { d = 10000000; }
		return c - d;
	} else {
		//alpha sort
		var c = a.getElementsByTagName('th')[0].firstChild.nodeValue.toLowerCase().replace(/ (\d\d)/,'$1');
		var d = b.getElementsByTagName('th')[0].firstChild.nodeValue.toLowerCase().replace(/ (\d\d)/,'$1');
		while( c.length > d.length ) { d += '~'; } while( d.length > c.length ) { c += '~'; }
		return ( d > c ) ? -1 : 1;
	}
}

function chartAll(oLess) {
	//get an array of rows
	for( var i = 0, rowArray = []; i < tableSorts.length - ( oLess ? 1 : 0 ); i++ ) {
		for( var j = 1; j < document.getElementById(tableSorts[i]).getElementsByTagName('tr').length; j++ ) {
			if( document.getElementById(tableSorts[i]).getElementsByTagName('tr')[j].className == 'disabl' ) { continue; }
			rowArray[rowArray.length] = document.getElementById(tableSorts[i]).getElementsByTagName('tr')[j];
		}
	}
	showChart(rowArray,'All browsers - all platforms',true)
}

var isBrokenIE5Mac = window.ActiveXObject && navigator.platform.indexOf('Mac') + 1 && !navigator.__ice_version && ( !window.ScriptEngine || ScriptEngine().indexOf('InScript') == -1 ) && !window.opera;

if( document.createElement && document.childNodes ) {
	for( var y = 0, tableSorts = ['linspeed','macspeed','mac9speed','winspeed','supspeed']; y < tableSorts.length; y++ ) {
		var theTable = document.getElementById(tableSorts[y]);
		theTable.curSort = 0;
		firstRow = theTable.getElementsByTagName('tr')[0];
		for( var x = 0; x < firstRow.getElementsByTagName('th').length; x++ ) {
			firstRow.getElementsByTagName('th')[x].title = 'Sort table by ' + firstRow.getElementsByTagName('th')[x].firstChild.nodeValue;
			firstRow.getElementsByTagName('th')[x].myIndex = x;
			firstRow.getElementsByTagName('th')[x].onclick = function () {

				if( isBrokenIE5Mac ) {
					alert('Sorry, I would allow you to do this, but your browser (Internet Explorer 5.x on Mac) will just crash.\n\nOn OS 9 and below, try using Mozilla 1.2.1.\n\nOn OS X, try using Opera 7.5+, Mozilla, Firefox, Camino, Safari, OmniWeb 4.5+.\n\nBasically, try anything that is capable of running a simple DOM script without crashing.');
					return;
				}
				var oTBod = this.parentNode.parentNode;
				var oTab = oTBod.parentNode;

				//get an array of rows
				for( var i = 1, rowArray = []; i < oTBod.getElementsByTagName('tr').length; i++ ) {
					rowArray[rowArray.length] = oTBod.getElementsByTagName('tr')[i];
				}

				oTBod.getElementsByTagName('tr')[0].getElementsByTagName('th')[oTab.curSort].className = '';
				this.className = 'sortedby';
				window.sortingBy = this.myIndex;
				rowArray.sort(rowSort);

				for( var i =  1; rowArray[i]; i++ ) {
					oTBod.appendChild(rowArray[i]);
				}
				if( window.opera ) {
					//rendering bug - already reported ;)
					oTBod.lastChild.style.visibility = 'hidden';
					setTimeout('document.getElementById(\''+oTBod.parentNode.id+'\').tBodies[0].lastChild.style.visibility = \'\'',1);
				}

				oTab.curSort = this.myIndex;

			};
		}
		for( var i = 1, rowArray = []; i < theTable.getElementsByTagName('tr').length; i++ ) {
			theTable.getElementsByTagName('tr')[i].getElementsByTagName('th')[0].onclick = function (e) {

				if( !e ) { e = window.event; }
				if( e && ( e.shiftKey || e.altKey ) ) { return; }

				var browserName = this.firstChild.nodeValue.replace(/^\s*([a-z][a-z\s]*[a-z])\s*[\d\.]*\s*(\([\w\W]*)?$/i,'$1');
				var browserRegExp = new RegExp('^\\s*'+browserName+'\\s*[\\d\\.]*\\s*(\\([\\w\\W]*)?$','i');

				//get an array of rows
				for( var i = 0, rowArray = []; i < tableSorts.length; i++ ) {
					for( var j = 1; j < document.getElementById(tableSorts[i]).getElementsByTagName('tr').length; j++ ) {
						if( document.getElementById(tableSorts[i]).getElementsByTagName('tr')[j].className == 'disabl' ) { continue; }
						if( document.getElementById(tableSorts[i]).getElementsByTagName('tr')[j].getElementsByTagName('th')[0].firstChild.nodeValue.match(browserRegExp) ) {
							rowArray[rowArray.length] = document.getElementById(tableSorts[i]).getElementsByTagName('tr')[j];
						}
					}
				}

				showChart(rowArray,browserName,true);

			};
		}
		if( !isBrokenIE5Mac ) { firstRow.getElementsByTagName('th')[0].onclick(); }
		var oPara = document.createElement('p');
		theTable.parentNode.insertBefore(oPara,theTable.nextSibling);
		oPara.className = 'aftertable';
		oPara.appendChild(document.createElement('a'));
		oPara.firstChild.setAttribute('href','#');
		oPara.firstChild.appendChild(document.createTextNode('Show graphs'));
		oPara.firstChild.onclick = function () {

			var oTBod = this.parentNode.previousSibling.getElementsByTagName('tbody')[0];

			//get an array of rows
			for( var i = 1, rowArray = []; i < oTBod.getElementsByTagName('tr').length; i++ ) {
				if( oTBod.getElementsByTagName('tr')[i].className == 'disabl' ) { continue; }
				rowArray[rowArray.length] = oTBod.getElementsByTagName('tr')[i];
			}

			showChart(rowArray,this.parentNode.previousSibling.getElementsByTagName('caption')[0].firstChild.nodeValue.replace(/speed[\w\W]*$/,' browsers'))

			return false;

		};
	}

	var oPara = document.createElement('p');
	document.getElementById('resultList').parentNode.insertBefore(oPara,document.getElementById('resultList').nextSibling);
	oPara.appendChild(document.createTextNode('Note: you can also click the \'Show graphs\' button after each table to see '+
		'the graphs for the browsers on that specific platform, or click the browsers names to see the graphs for all versions '+
		'of that specific browser. To exclude any browsers from the graphs, Shift+Click or Alt+Click on the rows you want to exclude.'));
	var oPara = document.createElement('p');
	document.getElementById('resultList').parentNode.insertBefore(oPara,document.getElementById('resultList').nextSibling);
	oPara.className = 'aftertable';
	oPara.appendChild(document.createElement('a'));
	oPara.firstChild.setAttribute('href','#');
	oPara.firstChild.appendChild(document.createTextNode('Graph all'));
	oPara.firstChild.onclick = function () { chartAll(); return false; }
	var oPara = document.createElement('p');
	document.getElementById('resultList').parentNode.insertBefore(oPara,document.getElementById('resultList').nextSibling.nextSibling);
	oPara.className = 'aftertable andWider';
	oPara.appendChild(document.createElement('a'));
	oPara.firstChild.setAttribute('href','#');
	oPara.firstChild.appendChild(document.createTextNode('Graph all except supplementary'));
	oPara.firstChild.onclick = function () { chartAll(true); return false; }

	document.onclick = function (e) {
		if( !e ) { e = window.event; } if( !e ) { return; }
		if( !e.shiftKey && !e.altKey ) { return; }
		if( !e.target ) { e.target = e.srcElement; } if( !e.target ) { return; }
		var oTarg = e.target;
		while( oTarg && ( !oTarg.tagName || oTarg.tagName.toLowerCase() != 'tr' ) ) {
			oTarg = oTarg.parentNode;
		}
		if( oTarg ) {
			oTarg.className = oTarg.className ? '' : 'disabl';
		}
	};

}
