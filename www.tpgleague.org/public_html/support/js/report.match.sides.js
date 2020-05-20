
function changeSides(box) {
    sel = eval("document.forms['report_match_form'].side_selector_" + box + ".selectedIndex");
    if (sel == 0) { return true; }
    opp = (sel == 1) ? 2 : 1;
    if (box == 'h1a') {
        document.forms['report_match_form'].side_selector_h1h.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2a.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2h.selectedIndex = sel;
    } else if (box == 'h1h') {
        document.forms['report_match_form'].side_selector_h1a.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2a.selectedIndex = sel;
        document.forms['report_match_form'].side_selector_h2h.selectedIndex = opp;
    } else if (box == 'h2a') {
        document.forms['report_match_form'].side_selector_h1a.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h1h.selectedIndex = sel;
        document.forms['report_match_form'].side_selector_h2h.selectedIndex = opp;
    } else {
        document.forms['report_match_form'].side_selector_h1a.selectedIndex = sel;
        document.forms['report_match_form'].side_selector_h1h.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2a.selectedIndex = opp;
    }
}
