import { Outlet } from 'react-router';
import Navbar from '../Shared/Navbar/Navbar';

const Layout = () => {

    return (
        <div className='bg-base-100 print:bg-base-200'>
            <span className='print:hidden'><Navbar /></span>
            <div className='w-full print:bg-base-200'>
                <Outlet />
            </div>
        </div>
    );
};

export default Layout;