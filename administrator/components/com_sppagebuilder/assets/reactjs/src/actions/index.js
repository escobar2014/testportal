export const addRow = () => {
  return {
    type: 'ADD_ROW',
  }
};

export const addRowBottom = (index) => {
  return {
    type: 'ADD_ROW_BOTTOM',
    index: index
  }
};

export const addInnerRow = ( index, colIndex ) => {
  return {
    type: 'ADD_INNER_ROW',
    index: index,
    settings: {
      colIndex: colIndex
    }
  }
};


export const toggleRow = (id) => {

  return {
    type: 'ROW_TOGGLE',
    id: id,
  }
};


export const deleteRow = (index) => {
  return {
    type: 'DELETE_ROW',
    index: index
  }
};

export const cloneRow = (index) => {
  return {
    type: 'CLONE_ROW',
    index: index
  };
};

export const pasteRow = (options) => {
  return{
    type: 'PASTE_ROW',
    index: options.index,
    row: options.data
  }
}

export const saveSetting = (options) => {

  let newType = 'ROW_SETTING';

  if (options.type === 'row' )
  {
    newType = 'ROW_SETTING';
  }
  else if(options.type === 'column')
  {
    newType = 'COLUMN_SETTING';
  }
  else if(options.type === 'addon')
  {
    if (options.settings.addonIndex === "") {
      newType = 'ADDON_SETTING';
    } else {
      newType = 'ADDON_EDIT';
    }
  }
  else if(options.type === 'inner_row')
  {
    newType = 'INNER_ROW_SETTING'
  }
  else if(options.type === 'inner_column')
  {
    newType = 'INNER_COLUMN_SETTING'
  }
  else if(options.type === 'inner_addon')
  {
    if (typeof options.settings.addonInnerIndex === 'undefined') {
      newType = 'ADDON_INNER_SETTING';
    }else{
      newType = 'ADDON_INNER_EDIT';
    }
  }

  return {
    type: newType,
    index: options.index,
    settings: options.settings
  }
};

export const cloneAddon = (options) => {
  return {
    type: 'CLONE_ADDON',
    index: options.index,
    settings: options.settings
  }
};

export const innerPasteRow = (options) => {
  var settings = {
    colIndex: options.colIndex,
    addonIndex: options.addonIndex,
    innerRow: options.data
  };

  return {
    type: 'PASTE_INNER_ROW',
    index: options.index,
    settings: settings
  }
};

export const innerCloneRow = (options) =>{
  return {
    type: 'CLONE_INNER_ROW',
    index: options.index,
    settings: options.settings
  }
};

export const cloneAddonInner = ( options ) =>{
  return {
    type: 'CLONE_INNER_ADDON',
    index: options.index,
    settings: options.settings
  }
}

export const deleteAddon = (options) => {
  return {
    type: 'DELETE_ADDON',
    index: options.index,
    settings: options.settings
  }
};

export const deleteInnerAddon = (options) => {
  return{
    type: 'DELETE_INNER_ADDON',
    index: options.index,
    settings: options.settings
  }
};

export const innerToggleRow = (options) => {
  return {
    type: 'INNER_ROW_TOGGLE',
    index: options.index,
    settings: options.settings
  }
};

/*-- */
export const innerAddRowBottom = (options) => {
  return {
    type: 'ADD_INNER_ROW_BOTTOM',
    index: options.index,
    settings: options.settings
  }
};

export const changeColumn = (layout, current, index) => {
  return {
    type: 'CHANGE_COLUMN',
    index: index,
    layout: layout,
    current: current

  }
};

export const changeInnerColumn = ( layout, current, index, colIndex, addonIndex ) => {
  return {
    type: 'CHANGE_INNER_COLUMN',
    index: index,
    layout: layout,
    current: current,
    settings: {
      colIndex: colIndex,
      addonIndex: addonIndex,
    }
  }
};

export const rowSortable = (dragIndex, hoverIndex) => {
  return {
    type : 'ROW_SORT',
    dragIndex : dragIndex,
    hoverIndex : hoverIndex
  }
};

export const importPage = (page) => {
  return {
    type: 'IMPORT_PAGE',
    page: page
  }
}

export const deleteColumn = (index, colIndex) => {
  return {
    type: 'DELETE_COLUMN',
    index: index,
    settings: {
      colIndex: colIndex
    }
  }
}

export const disableColumn = (index, colIndex, id) => {
  return {
    type: 'TOGGLE_COLUMN',
    index: index,
    settings: {
      colIndex: colIndex,
      id: id
    }
  }
}

export const deleteInnerColumn = (options) => {
  return {
    type: 'DELETE_INNER_COLUMN',
    index: options.index,
    settings: options.settings
  }
}

export const disableInnerColumn = (options) => {
  return {
    type: 'TOGGLE_INNER_COLUMN',
    index: options.index,
    settings: options.settings
  }
}

export const columnSortable = (rowIndex, dragIndex, hoverIndex) => {
  return {
    type : 'COLUMN_SORT',
    rowIndex: rowIndex,
    dragIndex : dragIndex,
    hoverIndex : hoverIndex
  }
}

export const toggleCollapse = ( id ) => {
  return {
    type : 'TOGGLE_COLLAPSE',
    id: id
  }
}

export const innerColumnSortable = (rowIndex, colIndex, addonIndex, dragIndex, hoverIndex) => {
  return {
    type        : 'INNER_COLUMN_SORT',
    rowIndex    : rowIndex,
    colIndex    : colIndex,
    addonIndex  : addonIndex,
    dragIndex   : dragIndex,
    hoverIndex  : hoverIndex
  }
}

export const disableAddon = (options) => {
  return {
    type: 'DISABLE_ADDON',
    index: options.index,
    settings: options.settings
  }
}

export const disableInnerAddon = (options) => {
  return {
    type: 'DISABLE_INNER_ADDON',
    index: options.index,
    settings: options.settings
  }
}
