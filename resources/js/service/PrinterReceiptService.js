import ExceptionHandling from './ExceptionHandling.js'
import { useStore, ActionTypes } from "../store";
import instance from './index';

export default class PrinterReceiptService {
	getItems(storeID,keyword,start) {
		//SHOW LOADING
		const store = useStore();
		store.dispatch(ActionTypes.PROGRESS_BAR, true);
		const api = '/api/printer_receipt_list';
		const formData = new FormData();
		formData.append('keyword', keyword);
		formData.append('start', start);
		formData.append('storeID', storeID);
		return instance()(
			{
				method: 'post',
				url: api,
				data: formData,
			}
		).then(res => res.data)
		.catch((e) => ExceptionHandling.HandleErrors(e))
		.finally(() => {
			store.dispatch(ActionTypes.PROGRESS_BAR, false);
		})
	}

	saveItem(postObj,stateObj,currentStoreID) {
		//SHOW LOADING
		const store = useStore();
		store.dispatch(ActionTypes.PROGRESS_BAR, true);
		const api = '/api/add_printer_receipt';
		const formData = new FormData();
		formData.append('receipt_heading', postObj.receiptHeading);
		formData.append('receipt_content', postObj.receiptContent);
		formData.append('receipt_priority', stateObj.receiptPriority);
		formData.append('status', postObj.status);
		formData.append('branch_id', currentStoreID);
		return instance()(
			{
				method: 'post',
				url: api,
				data: formData,
			}
		).then(res => res.data)
			.catch((e) => ExceptionHandling.HandleErrors(e))
			.finally(() => {
				store.dispatch(ActionTypes.PROGRESS_BAR, false);
			})
	}

	updateItem(postObj,stateObj) {
		//SHOW LOADING
		const store = useStore();
		store.dispatch(ActionTypes.PROGRESS_BAR, true);
		const api = '/api/edit_printer_receipt';
		const formData = new FormData();
		formData.append('id', postObj.id);
		formData.append('receipt_heading', postObj.receiptHeading);
		formData.append('receipt_content', postObj.receiptContent);
		formData.append('receipt_priority', stateObj.receiptPriority);

		return instance()(
			{
				method: 'post',
				url: api,
				data: formData,
			}
		).then(res => res.data)
			.catch((e) => ExceptionHandling.HandleErrors(e))
			.finally(() => {
				store.dispatch(ActionTypes.PROGRESS_BAR, false);
			})
	}

	getItem(data) {
		//SHOW LOADING
		const store = useStore();
		store.dispatch(ActionTypes.PROGRESS_BAR, true);
		const api = '/api/get_printer_receipt/' + data.id;
		return instance().get(api)
			.then(res => res.data)
			.catch((e) => ExceptionHandling.HandleErrors(e))
			.finally(() => {
				store.dispatch(ActionTypes.PROGRESS_BAR, false);
			})
	}

	changeStatus(postObj) {
		//SHOW LOADING
		const store = useStore();
		store.dispatch(ActionTypes.PROGRESS_BAR, true);
		const api = '/api/delete_printer_receipt';
		const formData = new FormData();
		formData.append('id', postObj.id);
		formData.append('status', postObj.status);

		return instance()(
			{
				method: 'post',
				url: api,
				data: formData,
			}
		).then(res => res.data)
			.catch((e) => ExceptionHandling.HandleErrors(e))
			.finally(() => {
				store.dispatch(ActionTypes.PROGRESS_BAR, false);
			})
	}
}