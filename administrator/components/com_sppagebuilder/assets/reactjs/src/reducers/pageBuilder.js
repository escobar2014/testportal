import update from 'react/lib/update';

let nextNodeId = (new Date).getTime();

const pageBuilder = ( state = initialState, action ) => {

  switch (action.type) {

    case 'IMPORT_PAGE':
    return action.page;

    case 'ADD_ROW':
    return[
      ...state,
      row(undefined, action)
    ];

    case 'ROW_TOGGLE':
    case 'TOGGLE_COLLAPSE':
    return state.map(t => row(t, action));

    case 'ADD_ROW_BOTTOM':
    return [
      ...state.slice(0,action.index + 1 ),
      row(undefined, action),
      ...state.slice(action.index + 1 )
    ];

    case 'DELETE_ROW':
    return [
      ...state.slice(0,action.index ),
      ...state.slice(action.index + 1)
    ]

    case 'CLONE_ROW':
    var cloneRow =  Object.assign({}, state[action.index]);

    var cloneRow = jQuery.extend(true, {}, state[action.index]),
    newRow = changeObjectId(cloneRow);

    return [
      ...state.slice( 0, action.index + 1 ),
      newRow,
      ...state.slice( action.index + 1 )
    ];

    case 'PASTE_ROW':
    var copyRow = Object.assign({},action.row,{
      id: nextNodeId++
    });

    return [
      ...state.slice( 0, action.index ),
      copyRow,
      ...state.slice( action.index + 1 )
    ];

    case 'ROW_SETTING':
    case 'COLUMN_SETTING':
    case 'ADDON_SETTING':
    case 'ADDON_EDIT':
    case 'CLONE_ADDON':
    case 'CHANGE_INNER_COLUMN':
    case 'PASTE_INNER_ROW':
    case 'CLONE_INNER_ROW':
    case 'DELETE_ADDON':
    case 'ADD_INNER_ROW':
    case 'DELETE_COLUMN':
    case 'INNER_ROW_TOGGLE':
    case 'ADD_INNER_ROW_BOTTOM':
    case 'INNER_ROW_SETTING':
    case 'INNER_COLUMN_SETTING':
    case 'ADDON_INNER_SETTING':
    case 'CLONE_INNER_ADDON':
    case 'DELETE_INNER_ADDON':
    case 'ADDON_INNER_EDIT':
    case 'CHANGE_COLUMN':
    case 'TOGGLE_COLUMN':
    case 'DELETE_INNER_COLUMN':
    case 'TOGGLE_INNER_COLUMN':
    case 'DISABLE_ADDON':
    case 'DISABLE_INNER_ADDON':

    return state.map(function( rowValue, index ){
      if (index != action.index) {
        return rowValue;
      }

      return row( rowValue, action );
    });

    case 'ROW_SORT':
    var dragIndex = action.dragIndex,
    hoverIndex = action.hoverIndex;
    var dragRow = state[dragIndex];

    return update(state, {
      $splice: [
        [dragIndex, 1],
        [hoverIndex, 0, dragRow]
      ]
    });

    case 'ADDON_SORT_COL_INNER':
    var drag         = action.drag,
    drop         = action.drop,
    dragIndex    = action.dragIndex,
    hoverIndex   = action.hoverIndex;

    var dragAddon    = state[drag.rowIndex].columns[drag.colIndex].addons[dragIndex];

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              $splice: [
                [dragIndex, 1],
                [hoverIndex, 0, dragAddon]
              ]
            }
          }
        }
      }
    });

    case 'ADDON_SORT_COL':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = state[drag.rowIndex].columns[drag.colIndex].addons[dragIndex];

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              $splice: [
                [dragIndex, 1]
              ]
            }
          },
          [drop.colIndex]:{
            addons:{
              $splice: [
                [hoverIndex, 0, dragAddon]
              ]
            }
          }
        }
      }
    });

    case 'ADDON_SORT_OUTER_ROW':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = state[drag.rowIndex].columns[drag.colIndex].addons[dragIndex];

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              $splice: [
                [dragIndex, 1]
              ]
            }
          }
        }
      },
      [drop.rowIndex]:{
        columns:{
          [drop.colIndex]:{
            addons:{
              $splice: [
                [hoverIndex, 0, dragAddon]
              ]
            }
          }
        }
      }
    });

    case 'ADDON_SORT_PARENT_COL':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = innerAddon(state, action);

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drag.innerRowIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1]
                      ]
                    }
                  }
                }
              }
            }
          },
          [drop.colIndex]:{
            addons:{
              $splice: [
                [hoverIndex, 0, dragAddon]
              ]
            }
          }
        }
      }
    });

    case 'ADDON_SORT_PARENT_OUTER_ROW':

    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = innerAddon(state, action);

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drag.innerRowIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      },
      [drop.rowIndex]:{
        columns:{
          [drop.colIndex]:{
            addons:{
              $splice: [
                [hoverIndex, 0, dragAddon]
              ]
            }
          }
        }
      }
    });


    case 'INNER_ADDON_SORT_INNER_COL':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var addon = state[drag.rowIndex]
    .columns[drag.colIndex]
    .addons[drag.innerRowIndex];

    var addonIndex = drag.innerRowIndex;
    if(typeof addon ==='undefined'){
      var addonIndex = Number(drag.innerRowIndex-1);
    }

    var dragAddon = innerAddon(state, action);

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [addonIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1],
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });


    case 'INNER_ADDON_SORT_INNER_ROW':

    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = innerAddon(state, action);

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drag.innerRowIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1]
                      ]
                    }
                  },
                  [drop.innerColIndex]:{
                    addons:{
                      $splice: [
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    case 'INNER_ADDON_SORT_OUTER_ROW':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = innerAddon(state, action);

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drag.innerRowIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1]
                      ]
                    }
                  }
                }
              },
              [drop.innerRowIndex]:{
                columns:{
                  [drop.innerColIndex]:{
                    addons:{
                      $splice: [
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    case 'INNER_ADDON_SORT_PARENT_ROW':

    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = innerAddon(state, action);

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drag.innerRowIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1]
                      ]
                    }
                  }
                }
              }
            }
          },
          [drop.colIndex]:{
            addons:{
              [drop.innerRowIndex]:{
                columns:{
                  [drop.innerColIndex]:{
                    addons:{
                      $splice: [
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    case 'INNER_ADDON_SORT_PARENT_OUTER_ROW':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = innerAddon(state, action);

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drag.innerRowIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      },
      [drop.rowIndex]:{
        columns:{
          [drop.colIndex]:{
            addons:{
              [drop.innerRowIndex]:{
                columns:{
                  [drop.innerColIndex]:{
                    addons:{
                      $splice: [
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    case 'ADDON_SORT_INNER_ADDON_ROW':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = state[drag.rowIndex].columns[drag.colIndex].addons[dragIndex];

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              $splice: [
                [dragIndex, 1]
              ]
            }
          },
          [drop.colIndex]:{
            addons:{
              [drop.innerRowIndex]:{
                columns:{
                  [drop.innerColIndex]:{
                    addons:{
                      $splice: [
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    case 'ADDON_SORT_INNER_ADDON_OUTER_ROW':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = state[drag.rowIndex].columns[drag.colIndex].addons[dragIndex];

    return update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              $splice: [
                [dragIndex, 1]
              ]
            }
          }
        }
      },
      [drop.rowIndex]:{
        columns:{
          [drop.colIndex]:{
            addons:{
              [drop.innerRowIndex]:{
                columns:{
                  [drop.innerColIndex]:{
                    addons:{
                      $splice: [
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    case 'ADDON_SORT_PARENT_COL_INNER':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = innerAddon(state, action);

    var newAddon = update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drag.innerRowIndex]:{
                columns:{
                  [drag.innerColIndex]:{
                    addons:{
                      $splice: [
                        [dragIndex, 1]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    return update(newAddon,{
      [drop.rowIndex]:{
        columns:{
          [drop.colIndex]:{
            addons:{
              $splice: [
                [hoverIndex, 0, dragAddon]
              ]
            }
          }
        }
      }
    });

    case 'ADDON_SORT_INNER_ADDON_COL':
    var drag        = action.drag,
    drop        = action.drop,
    dragIndex   = action.dragIndex,
    hoverIndex  = action.hoverIndex;

    var dragAddon = state[drag.rowIndex].columns[drag.colIndex].addons[dragIndex];

    var newAddon = update(state,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              [drop.innerRowIndex]:{
                columns:{
                  [drop.innerColIndex]:{
                    addons:{
                      $splice: [
                        [hoverIndex, 0, dragAddon]
                      ]
                    }
                  }
                }
              }
            }
          }
        }
      }
    });

    return update(newAddon,{
      [drag.rowIndex]:{
        columns:{
          [drag.colIndex]:{
            addons:{
              $splice: [
                [dragIndex, 1]
              ]
            }
          }
        }
      }
    });

    case 'COLUMN_SORT':
    case 'INNER_COLUMN_SORT':
    var rowIndex   = action.rowIndex;

    return state.map(function( rowValue, index ){
      if ( index != rowIndex) {
        return rowValue;
      }
      return row( rowValue, action );
    });

    default:
    return state;
  }
}


const innerAddon = ( state, action ) => {
  var drag        = action.drag,
  drop        = action.drop,
  dragIndex   = action.dragIndex,
  hoverIndex  = action.hoverIndex;

  var addon = state[drag.rowIndex]
  .columns[drag.colIndex]
  .addons[drag.innerRowIndex];

  var addonIndex = drag.innerRowIndex;
  if(typeof addon ==='undefined'){
    var addonIndex = Number(drag.innerRowIndex-1);
  }

  return state[drag.rowIndex]
  .columns[drag.colIndex]
  .addons[addonIndex]
  .columns[drag.innerColIndex]
  .addons[dragIndex];
}

const initRow = (rowId, colId) =>{
  return{
    id: rowId,
    visibility: true,
    collapse: false,
    settings:{
      padding: '50px 0px 50px 0px',
      margin: '0px 0px 0px 0px'
    },
    layout: '12',
    columns: [
      {
        id: colId,
        class_name: 'col-md-12',
        visibility: true,
        settings: {},
        addons:[]
      }
    ]
  }
}

const row = (state,action) => {

  switch(action.type){

    case 'ADD_ROW':
    return initRow( nextNodeId++, nextNodeId++ );

    case 'ROW_TOGGLE':
    if (state.id !== action.id) {
      return state;
    };

    return Object.assign({},state,{
      visibility: !state.visibility
    });

    case 'TOGGLE_COLLAPSE':
    if (state.id !== action.id) {
      return state;
    };

    return Object.assign({},state,{
      collapse: !state.collapse
    });

    case 'ADD_ROW_BOTTOM':
    return initRow( nextNodeId++, nextNodeId++ );

    case 'ROW_SETTING':
    return Object.assign({},state,{
      settings: action.settings.formData
    });

    case 'CHANGE_COLUMN':
    var cloneCols = jQuery.extend(true, {}, state.columns);
    var colObj = changeColumnLayout(cloneCols, action);

    return Object.assign({},state,{
      layout: colObj.layout,
      columns: colObj.columns
    });

    case 'COLUMN_SETTING':

    var startCol    = Number( action.settings.colIndex ),
    endCol      = startCol + 1;

    var clonedColumn = Object.assign({}, state.columns[action.settings.colIndex]);
    clonedColumn.settings = action.settings.formData;

    return Object.assign({},state,{
      columns: [
        ...state.columns.slice( 0, startCol ),
        clonedColumn,
        ...state.columns.slice( endCol )
      ]
    }
  );

  case 'ADDON_SETTING':
  case 'ADDON_EDIT':
  case 'CLONE_ADDON':
  case 'PASTE_INNER_ROW':
  case 'CLONE_INNER_ROW':
  case 'CHANGE_INNER_COLUMN':
  case 'DELETE_ADDON':
  case 'ADD_INNER_ROW':
  case 'INNER_ROW_TOGGLE':
  case 'ADD_INNER_ROW_BOTTOM':
  case 'INNER_ROW_SETTING':
  case 'INNER_COLUMN_SETTING':
  case 'ADDON_INNER_SETTING':
  case 'CLONE_INNER_ADDON':
  case 'DELETE_INNER_ADDON':
  case 'ADDON_INNER_EDIT':
  case 'TOGGLE_COLUMN':
  case 'DELETE_INNER_COLUMN':
  case 'TOGGLE_INNER_COLUMN':
  case 'DISABLE_ADDON':
  case 'DISABLE_INNER_ADDON':

  var startCol  = Number( action.settings.colIndex ),
  endCol    = startCol + 1,
  column    = Object.assign({}, state.columns[startCol]);

  return Object.assign({},state,{
    columns: [
      ...state.columns.slice( 0, startCol ),
      addons(column,action),
      ...state.columns.slice( endCol )
    ]
  }
);

case 'DELETE_COLUMN':
var startCol  = Number( action.settings.colIndex ),
endCol    = startCol + 1,
layout    = state.layout,
arrLayout = layout.split(',');
arrLayout.splice(startCol,1);

var newarrLayout = arrLayout.join(',');

return Object.assign({},state,{
  layout: newarrLayout,
  columns: [
    ...state.columns.slice( 0, startCol ),
    ...state.columns.slice( endCol )
  ]
}
);

case 'COLUMN_SORT':
var dragIndex = action.dragIndex,
hoverIndex = action.hoverIndex;

const { columns } = state;
const dragCol = columns[dragIndex];

return update(state, {
  columns: {
    $splice: [
      [dragIndex, 1],
      [hoverIndex, 0, dragCol]
    ]
  }
});

case 'INNER_COLUMN_SORT':

var colIndex    = action.colIndex,
addonIndex  = action.addonIndex,
dragIndex   = action.dragIndex,
hoverIndex  = action.hoverIndex,
dragColumn  = state.columns[colIndex].addons[addonIndex].columns[dragIndex];

return update(state, {
  columns: {
    [colIndex]:{
      addons:{
        [addonIndex]:{
          columns:{
            $splice: [
              [dragIndex, 1],
              [hoverIndex, 0, dragColumn]
            ]
          }
        }
      }
    }
  }
});
default:
return state;
}
}

const columns = ( state, action ) => {

  var startCol  = Number( action.settings.innerColIndex ),
  endCol      = startCol + 1;

  switch(action.type) {
    case 'INNER_COLUMN_SETTING':

    var clonedColumn = Object.assign({}, state.columns[ startCol ]);
    clonedColumn.settings = action.settings.formData;

    return Object.assign({},state,{
      columns: [
        ...state.columns.slice( 0, startCol ),
        clonedColumn,
        ...state.columns.slice( endCol )
      ]
    }
  );

  case 'CHANGE_INNER_COLUMN':
  var cloneCols = jQuery.extend(true, {}, state.columns);
  var innerColObj = changeColumnLayout(cloneCols, action);

  return Object.assign({},state,{
    layout: innerColObj.layout,
    columns: innerColObj.columns
  });

  case 'DELETE_INNER_COLUMN':

  var startCol  = Number( action.settings.innerColIndex ),
  endCol    = startCol + 1,
  layout    = state.layout,
  arrLayout = layout.split(',');
  arrLayout.splice(startCol,1);

  var newarrLayout = arrLayout.join(',');

  return Object.assign({},state,{
    layout: newarrLayout,
    columns: [
      ...state.columns.slice( 0, startCol ),
      ...state.columns.slice( endCol )
    ]
  }
);

case 'ADDON_INNER_SETTING':
case 'CLONE_INNER_ADDON':
case 'DELETE_INNER_ADDON':
case 'ADDON_INNER_EDIT':
case 'DISABLE_INNER_ADDON':

if (action.type === 'ADDON_INNER_SETTING') {
  action.type = 'ADDON_SETTING';
} else {
  action.settings.addonIndex = action.settings.addonInnerIndex;
}

if (action.type === 'CLONE_INNER_ADDON') {
  action.type = 'CLONE_ADDON';
};

if (action.type === 'DELETE_INNER_ADDON') {
  action.type = 'DELETE_ADDON';
};

if (action.type === 'ADDON_INNER_EDIT') {
  action.type = 'ADDON_EDIT';
};

if (action.type === 'DISABLE_INNER_ADDON') {
  action.type = 'DISABLE_ADDON';
};

var clonedColumn = Object.assign({}, state.columns[ startCol ]);

return Object.assign({},state,{
  columns: [
    ...state.columns.slice( 0, startCol ),
    addons(clonedColumn, action),
    ...state.columns.slice( endCol )
  ]
}
);

case 'TOGGLE_INNER_COLUMN':
var clonedInnerColumn = Object.assign({}, state.columns[ startCol ]);
clonedInnerColumn.visibility = !clonedInnerColumn.visibility;

return Object.assign({},state,{
  columns: [
    ...state.columns.slice( 0, startCol),
    clonedInnerColumn,
    ...state.columns.slice( endCol )
  ]
}
);

case 'DISABLE_ADDON':
const { visibility } = state;

return Object.assign({},state,{
  visibility: !visibility
}
);

default:
return state;
}
}

// reducers
const addons = (state,action) => {

  var start = Number(action.settings.addonIndex),
  end   = start + 1;

  var options = action.settings;

  var row = {
    id: nextNodeId++,
    type: 'inner_row',
    settings: {},
    layout: '12',
    visibility: true,
    columns: [
      {
        id: nextNodeId++,
        class_name: 'col-md-12',
        visibility: true,
        settings: {},
        addons: []
      }
    ]
  };

  switch(action.type) {

    case 'ADDON_SETTING':
    var newAddon = {
      id: nextNodeId++,
      name: options.addonName,
      visibility: true,
      settings: options.formData,
    };

    return Object.assign({},state,{
      addons: [
        ...state.addons,
        newAddon
      ]
    }
  );


  case 'TOGGLE_COLUMN':
  if (state.id !== action.settings.id) {
    return state;
  };

  return Object.assign({},state,{
    visibility: !state.visibility
  });

  case 'ADD_INNER_ROW':

  return Object.assign({},state,{
    addons: [
      ...state.addons,
      row
    ]
  }
);

case 'ADD_INNER_ROW_BOTTOM':

return Object.assign({},state,{
  addons: [
    ...state.addons.slice( 0, end ),
    row,
    ...state.addons.slice( end )
  ]
}
);

case 'CLONE_ADDON':
var clonedAddon = Object.assign({}, state.addons[ start ]);
clonedAddon.id = nextNodeId++;

return Object.assign({},state,{
  addons: [
    ...state.addons.slice( 0, end ),
    clonedAddon,
    ...state.addons.slice( end )
  ]
}
);

case 'PASTE_INNER_ROW':
var copyRow = Object.assign({},action.settings.innerRow,{
  id: nextNodeId++
});

return Object.assign({},state,{
  addons: [
    ...state.addons.slice( 0, start ),
    copyRow,
    ...state.addons.slice( end )
  ]
}
);

case 'CLONE_INNER_ROW':
var clonedAddon = jQuery.extend(true, {}, state.addons[ start ]),
newAddon = changeObjectId(clonedAddon);

return Object.assign({},state,{
  addons: [
    ...state.addons.slice( 0, end ),
    newAddon,
    ...state.addons.slice( end )
  ]
}
);

case 'INNER_ROW_TOGGLE':

var clonedAddon = Object.assign({}, state.addons[ start ]);
clonedAddon.visibility = !clonedAddon.visibility;

return Object.assign({},state,{
  addons: [
    ...state.addons.slice( 0, start),
    clonedAddon,
    ...state.addons.slice( end )
  ]
}
);


case 'ADDON_EDIT':
case 'INNER_ROW_SETTING':

var clonedAddon = Object.assign({}, state.addons[ start ]);
clonedAddon.settings    = options.formData;

return Object.assign({},state,{
  addons: [
    ...state.addons.slice( 0, start),
    clonedAddon,
    ...state.addons.slice( end )
  ]
}
);

case 'INNER_COLUMN_SETTING':
case 'ADDON_INNER_SETTING':
case 'CLONE_INNER_ADDON':
case 'DELETE_INNER_ADDON':
case 'CHANGE_INNER_COLUMN':
case 'ADDON_INNER_EDIT':
case 'DELETE_INNER_COLUMN':
case 'TOGGLE_INNER_COLUMN':
case 'DISABLE_ADDON':
case 'DISABLE_INNER_ADDON':

var clonedAddon = Object.assign({}, state.addons[ start ]);

return Object.assign({},state,{
  addons: [
    ...state.addons.slice( 0, start),
    columns(clonedAddon,action),
    ...state.addons.slice( end )
  ]
}
);

case 'DELETE_ADDON':
return Object.assign({},state,{
  addons: [
    ...state.addons.slice(0,action.settings.addonIndex),
    ...state.addons.slice(action.settings.addonIndex+1)
  ]
}
);

default:
return state;
}
};

const changeObjectId = (obj) => {
  obj.id = nextNodeId++;
  jQuery.each(obj.columns, function(key, column){
    column.id = nextNodeId++;
    jQuery.each(column.addons,function(key,addon){
      if ( addon.type === 'inner_row' ) {
        return changeObjectId(addon);
      } else {
        addon.id = nextNodeId++;
      }
    });
  });

  return obj;
};

const changeColumnLayout = ( columns, action ) => {
  var cloneCols   = columns,
  layout      = action.layout,
  current     = action.current;

  if (layout == '12') {
    var layoutArray = [12];
  } else {
    var layoutArray     = layout.split(',')
  }

  if (current == '12') {
    var activeLayout = [12];
  } else {
    var activeLayout     = current.split(',')
  }

  var newLength       = layoutArray.length,
  currentLength   = activeLayout.length;

  var newColumns  = [];

  for ( var i = 0; i < newLength; i++ ) {
    var element = layoutArray[i],
    newClassName = 'col-md-' + element;

    if (typeof cloneCols[i] !== 'undefined') {
      cloneCols[i].class_name = newClassName;
      newColumns.push(cloneCols[i]);
    } else {
      var newCoulmn = {
        id: nextNodeId++,
        class_name: newClassName,
        visibility: true,
        settings: {},
        addons: []
      };

      newColumns.push(newCoulmn);
    }
  }

  if ( newLength < currentLength ) {
    var index = newLength - 1,
    restList = [];

    for ( var i = newLength; i < currentLength; i++ ) {
      var restAddons = cloneCols[i].addons;
      if(restAddons.length > 0){
        restList = restList.concat(restAddons);
      }
    }

    if (restList.length > 0) {
      newColumns[index].addons = newColumns[index].addons.concat(restList);
    }
  };

  return {
    layout: layout,
    columns: newColumns
  }
}

export default pageBuilder
