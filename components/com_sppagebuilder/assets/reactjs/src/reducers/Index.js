import { combineReducers } from 'redux'
import pageBuilder from './pageBuilder';
import undoable from 'redux-undo';

const pageBuilderCont = combineReducers({
  pageBuilder: undoable(pageBuilder)
});

export default pageBuilderCont;
