import { fetchWrapper } from '@/utils/helpers/fetch-wrapper';
import { useProfileStore } from '@/stores/profileStore';
const profileStore = useProfileStore();


async function updatePersonalData(otp:number) { 

    try { 
        const response = await fetchWrapper.post('/profile/personal-data', { ...profileStore.userData, otp });
        return response
    } catch (error) {
        console.error(error);
        throw error;
    }


}


async function updateFinancialData(data : any, otp:number) { 

    try { 
        const response = await fetchWrapper.post('/profile/financial-data', { data,otp });
        return response
    } catch (error) {
        console.error(error);
        throw error;
    }


}


export { updatePersonalData, updateFinancialData}