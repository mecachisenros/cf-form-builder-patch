import { prepareProcessorsForSave } from "@calderajs/form-builder";

export const patchedPrepareProcessorsForSave = (processors, forSave) => {
    const savedProcessors = prepareProcessorsForSave(processors, forSave);

    // ensure processors are in the same order as the UI
	const forSaveProcessors = Object.entries(forSave).map(([processorId]) => {
		return [processorId, savedProcessors[processorId]];
    });

	return Object.fromEntries(forSaveProcessors);
}
