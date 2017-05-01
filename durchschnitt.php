<p>
<select id="teamSelect" size="3" multiple="multiple"></select>
<select id="jahrSelect" style="vertical-align: top;"></select>
<input id="nurMeisterschaftCb" style="margin: 0; vertical-align: top;" type="checkbox" name="meisterschaft"><span style="vertical-align: top;">&nbsp;Nur Meisterschaft</span>
</p>

<table id="table" style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
	<colgroup>
		<col style="width: 101pt;" span="7" width="134">
	</colgroup>
	<tbody>
		<tr id="tableheader" style="height: 15.75pt;">
			<td style="height: 15.75pt; width: 101pt; font-size: 10pt; color: white; font-weight: bold; font-family: Calibri; border-top-width: 1pt; border-style: solid none solid solid; border-top-color: #4f81bd; border-bottom-width: 1pt; border-bottom-color: #4f81bd; border-left-width: 0.5pt; border-left-color: #95b3d7; background: #4f81bd;" width="134" height="21" data-sorttype="string" data-sort="nachname">Nachname</td>
			<td style="width: 101pt; font-size: 10pt; color: white; font-weight: bold; font-family: Calibri; border-top-width: 1pt; border-style: solid none; border-top-color: #4f81bd; border-bottom-width: 1pt; border-bottom-color: #4f81bd; background: #4f81bd;" width="134" data-sorttype="string" data-sort="vorname">Vorname</td>
			<td style="width: 101pt; font-size: 10pt; color: white; font-weight: bold; font-family: Calibri; border-top-width: 1pt; border-style: solid none; border-top-color: #4f81bd; border-bottom-width: 1pt; border-bottom-color: #4f81bd; background: #4f81bd; text-align: right;" width="134" data-sorttype="numeric" data-sort="punkte">Punkte</td>
			<td style="width: 101pt; font-size: 10pt; color: white; font-weight: bold; font-family: Calibri; border-top-width: 1pt; border-style: solid none; border-top-color: #4f81bd; border-bottom-width: 1pt; border-bottom-color: #4f81bd; background: #4f81bd; text-align: right;" width="134" data-sorttype="numeric" data-sort="streiche">Streiche</td>
			<td style="width: 101pt; font-size: 10pt; color: white; font-weight: bold; font-family: Calibri; border-top-width: 1pt; border-style: solid none; border-top-color: #4f81bd; border-bottom-width: 1pt; border-bottom-color: #4f81bd; background: #4f81bd; text-align: right;" width="134" data-sorttype="numeric" data-sort="schnitt">Durchschnitt</td>
			<td style="width: 101pt; font-size: 10pt; color: white; font-weight: bold; font-family: Calibri; border-top-width: 1pt; border-style: solid none; border-top-color: #4f81bd; border-bottom-width: 1pt; border-bottom-color: #4f81bd; background: #4f81bd; text-align: right;" width="134" data-sorttype="numeric" data-sort="diff">Veränderung Vorjahr</td>
			<td style="width: 101pt; font-size: 10pt; color: white; font-weight: bold; font-family: Calibri; border-top-width: 1pt; border-style: solid solid solid none; border-top-color: #4f81bd; border-right-width: 0.5pt; border-right-color: #95b3d7; border-bottom-width: 1pt; border-bottom-color: #4f81bd; background: #4f81bd; text-align: right; padding-right: 10px;" width="134" data-sorttype="numeric" data-sort="rangpunkte">Rangpunkte</td>
		</tr>
</table>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/qooxdoo/4.1/q.min.js"></script>

<script type="text/javascript">
	(function() {
		var club = 'bramberg';
		var sortColumn, sortDirection, sortType, data;
		q.ready(function() {
			var xmlhttp = q.io.xhr("https://www.hgverwaltung.ch/api/1/" + club + "/spiele/jahre");
			xmlhttp.on('loadend', function(xhr) {
				if (xhr.responseText) {
					var jahre = JSON.parse(xhr.responseText);
					var jahrSelect = q("#jahrSelect");
					jahre.forEach(function(j) {
						jahrSelect.append('<option value=' + j + '>' + j + '</option>');
					});
					jahrSelect.on("change", getData);
					q("#nurMeisterschaftCb").on("change", getData);
					getData();
				}
			});
			xmlhttp.send();
			var xmlhttpteam = q.io.xhr("https://www.hgverwaltung.ch/api/1/" + club + "/mannschaften");
			xmlhttpteam.on('loadend', function(xhr) {
				if (xhr.responseText) {
					var teams = JSON.parse(xhr.responseText);
					var teamSelect = q("#teamSelect");
					teams.forEach(function(j) {
						teamSelect.append('<option value=' + j + '>' + j + '</option>');
					});
					teamSelect.on("change", getData);
					getData();
				}
			});
			xmlhttpteam.send();
			q("#tableheader td").on("click", onHeaderClick);
		});
		function getData() {
			var jahr = q("#jahrSelect").getValue();
			var team = q("#teamSelect").getValue();
			var nurMeisterschaft = q("#nurMeisterschaftCb").getProperty('checked');
			if (jahr) {
				sortColumn = 'schnitt';
				sortDirection = '-';
				sortType = 'numeric';
				var alle = nurMeisterschaft ? '0' : '1';
				var xmlhttp = q.io.xhr("https://www.hgverwaltung.ch/api/1/" + club + "/durchschnitt/" + team + "?sort=schnitt&alle=" + alle + "&rangpunkteDetail=true&excludeSpieler=Ersatz&jahr=" + jahr);
				xmlhttp.on('loadend', function(xhr) {
					if (xhr.responseText) {
						data = JSON.parse(xhr.responseText);
						showData(data);
					}
				});
				xmlhttp.send();
			}
		}
		function showData(datas) {
			for (var i = 0; i < datas.length; i++) {
				var data = datas[i];
				if (data.schnitt && data.schnittVorjahr) {
					data.diff = parseFloat(data.schnitt) - parseFloat(data.schnittVorjahr);
				}
			}
			datas.sort(createSortFunction());
			q("#table tr.g").remove();
			var style = ['width: 101pt; font-size: 10pt; color: #365f91; font-family: Calibri; border-top-width: 0.5pt; border-style: solid none; border-top-color: #95b3d7; border-bottom-width: 0.5pt; border-bottom-color: #95b3d7; background: #d3dfee;', 'height: 15pt; width: 101pt; font-size: 10pt; color: #365f91; font-weight: bold; font-family: Calibri; border-top-width: 0.5pt; border-style: solid none solid solid; border-top-color: #95b3d7; border-bottom-width: 0.5pt; border-bottom-color: #95b3d7; border-left-width: 0.5pt; border-left-color: #95b3d7;'];
			var ix = 0;
			for (var i = 0; i < datas.length; i++) {
				var data = datas[i];
				if (data.streiche === 0) {
					continue;
				}
				var si = ix % 2;
				ix++;
				var row = '<tr class="g" style="height: 15pt;">';
				row = row + '<td style="' + style[si] + '">' + data.nachname + '</td>';
				row = row + '<td style="' + style[si] + '">' + data.vorname + '</td>';
				row = row + '<td style="' + style[si] + 'text-align:right">' + data.punkte + '</td>';
				row = row + '<td style="' + style[si] + 'text-align:right">' + data.streiche + '</td>';
				if (data.schnitt) {
					row = row + '<td style="' + style[si] + 'text-align:right">' + data.schnitt.toFixed(2) + '</td>';
				} else {
					row = row + '<td style="' + style[si] + 'text-align:right"></td>';
				}
				if (data.diff) {
					row = row + '<td style="' + style[si] + 'text-align:right">' + data.diff.toFixed(2) + '</td>';
				} else {
					row = row + '<td style="' + style[si] + 'text-align:right"></td>';
				}
				row = row + '<td style="' + style[si] + 'text-align:right;padding-right:10px">' + data.rangpunkte + '</td>';
				row = row + '</tr>';
				q("#table > tbody:last").append(row);
			}
		}
		function createSortFunction() {
			if (sortType === 'string') {
				return function(a, b) {
					var x = a[sortColumn].toLowerCase();
					var y = b[sortColumn].toLowerCase();
					if (sortDirection !== '-') {
						return x < y ? -1 : x > y ? 1 : 0;
					} else {
						return x < y ? 1 : x > y ? -1 : 0;
					}
				}
			} else {
				return function(a, b) {
					if (sortDirection !== '-') {
						return a[sortColumn] - b[sortColumn];
					} else {
						return b[sortColumn] - a[sortColumn];
					}
				}
			}
		}
		function onHeaderClick(e) {
			var target = q(e.getTarget());
			var sort = target.getData('sort');
			if (sort) {
				if (sortColumn === sort) {
					if (sortDirection === '-') {
						sortDirection = '';
					} else {
						sortDirection = '-';
					}
				} else {
					sortColumn = sort;
					sortDirection = '';
					sortType = target.getData('sorttype');
				}
			}
			showData(data);
		}
	})();
</script>
