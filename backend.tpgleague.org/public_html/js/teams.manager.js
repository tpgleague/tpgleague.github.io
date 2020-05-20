sections = ['group1','group2','group3'];


/*
function createNewSection(name) {
    var name = $F('sectionName');
    if (name != '') {
        var newDiv = Builder.node('div', {id: 'group' + (sections.length + 1), className: 'section', style: 'display:none;' }, [
            Builder.node('h3', {className: 'handle'}, name)
        ]);

        sections.push(newDiv.id);
        $('page').appendChild(newDiv);
        Effect.Appear(newDiv.id);
        destroyLineItemSortables();
        createLineItemSortables();
        createGroupSortable();
    }
}
*/

function createLineItemSortables() {
    for(var i = 0; i < sections.length; i++) {
        Sortable.create(sections[i],{tag:'div',dropOnEmpty: true, containment: sections,only:'lineitem'});
    }
}

function destroyLineItemSortables() {
    for(var i = 0; i < sections.length; i++) {
        Sortable.destroy(sections[i]);
    }
}

function createGroupSortable() {
    Sortable.create('page',{tag:'div',only:'section',handle:'handle'});
}

/*
Debug Functions for checking the group and item order
*/
function getGroupOrder() {
    var sections = document.getElementsByClassName('section');
    var alerttext = '';
    sections.each(function(section) {
        var sectionID = section.id;
        var order = Sortable.serialize(sectionID);
        alerttext += sectionID + ': ' + Sortable.sequence(section) + '\n';
    });
    alert(alerttext);
    return false;
}