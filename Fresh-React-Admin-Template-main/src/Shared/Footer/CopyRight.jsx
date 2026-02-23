

const CopyRight = () => {
    return (
        <div className='bg-secondary border-t border-base-300'>
            <div className="max-w-7xl px-4 mx-auto py-4 flex justify-between items-center">
                <p>Copyright © {new Date().getFullYear()} Wbsoftwares</p>
                <div className="h-4 sm:h-6">
                    {/* <img src={PaymentImg} alt="PaymentImg" className="h-full" /> */}
                </div>
            </div>
        </div>
    );
};

export default CopyRight;