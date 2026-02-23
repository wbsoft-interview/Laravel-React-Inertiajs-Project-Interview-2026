import { Outlet } from 'react-router';
import AdminNavbar from '../Shared/Navbar/AdminNavbar';
import Loader from '../componentes/Loader';
import UseAuth from '../Hooks/UseAuth';
import { IoSettingsSharp } from 'react-icons/io5';
import { useState } from 'react';
import InstituteSetting from '../Pages/InstituteSetting/InstituteSetting';

const AdminLayout = () => {
    const { loading } = UseAuth();
    const [panelHide, setPanelHide] = useState(true);
    const [open, setOpen] = useState(false);

    const closeAddPanel = () => {
        setPanelHide(false);
        setTimeout(() => {
            setOpen(false);
            setPanelHide(true);
        }, 300);
    };

    return (
        <div className=' relative bg-base-100 print:bg-base-200'>
            <span className='print:hidden'><AdminNavbar /></span>
            <div className='w-full min-h-[calc(100vh-70px)] print:bg-base-200'>
                <Outlet />
            </div>

            {open ?
                <InstituteSetting panelHide={panelHide} closeAddPanel={closeAddPanel} /> :
                <IoSettingsSharp size={24} className='fixed top-1/3 right-2 z-50 hover:text-primary cursor-pointer print:hidden' onClick={() => setOpen(!open)} />}


            <div className={loading ? `fixed top-0 left-0 z-50 bg-black/20 w-full h-screen print:hidden` : "hidden"}>
                <Loader />
            </div>
        </div>
    );
};

export default AdminLayout;