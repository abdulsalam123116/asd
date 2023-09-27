import ExceptionHandling from "./ExceptionHandling.js";
import { useStore, ActionTypes } from "../store";
import instance from "./index";

export default class PrinterCommandService {
    savePrinterCommand(
        dataObj,
        printer_type,
        branch_id,
        user_id
    ) {
        // SHOW LOADING
        const store = useStore();
        store.dispatch(ActionTypes.PROGRESS_BAR, true);
        const api = "/api/printer-commands";
        const formData = new FormData();
        formData.append("data", JSON.stringify(dataObj));
        formData.append("printer_type", printer_type);
        formData.append("branch_id", branch_id);
        formData.append("user_id", user_id);
        return instance()({
            method: "post",
            url: api,
            data: formData,
        })
            .then((res) => res.data)
            .catch((e) => ExceptionHandling.HandleErrors(e))
            .finally(() => {
                store.dispatch(ActionTypes.PROGRESS_BAR, false);
            });
    }

    savePrinterCommandCount(
        dataObj,
        printer_type,
        branch_id,
        user_id,
        count = 1
    ) {
        // SHOW LOADING
        const store = useStore();
        store.dispatch(ActionTypes.PROGRESS_BAR, true);
        const api = "/api/printer-commands-count";
        const formData = new FormData();
        formData.append("data", JSON.stringify(dataObj));
        formData.append("printer_type", printer_type);
        formData.append("branch_id", branch_id);
        formData.append("user_id", user_id);
        formData.append("count", count);
        return instance()({
            method: "post",
            url: api,
            data: formData,
        })
            .then((res) => res.data)
            .catch((e) => ExceptionHandling.HandleErrors(e))
            .finally(() => {
                store.dispatch(ActionTypes.PROGRESS_BAR, false);
            });
    }
}
