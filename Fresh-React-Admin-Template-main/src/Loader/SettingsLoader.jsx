import Skeleton, { SkeletonTheme } from "react-loading-skeleton";
import 'react-loading-skeleton/dist/skeleton.css';

const SettingSkeleton = () => (
    <div className="pl-4 pr-2 flex items-center justify-between gap-3">
         <Skeleton circle height={64} width={64} />
        <div className="space-y-2 w-full">
            <div className="flex items-center justify-between">
                <Skeleton width={64} height={16} />
                <Skeleton width={32} height={16} />
            </div>
            <div className="flex items-center justify-between gap-5">
                <Skeleton width={240} height={16} />
                <Skeleton width={16} height={16} />
            </div>
        </div>
    </div>
);
const SettingSkeleton2 = () => (
    <div className="pl-4 pr-2 flex items-center justify-between gap-3">
        <div className="space-y-2 w-full">
             <Skeleton width={44} height={44} />
            <div className="space-y-3">
                <Skeleton width={44} height={26} />
                <Skeleton width={64} height={26} />
            </div>
        </div>
    </div>
);

const SettingsLoader = () => {
    return (
        <SkeletonTheme baseColor="#e0e0e0" highlightColor="#f5f5f5">
            <div className="space-y-3 pt-3" aria-busy="true" role="status">

                {/* Chat Items */}
                {[...Array(1)].map((_, i) => (
                    <SettingSkeleton key={i} />
                ))}

                 {/* Chat Items */}
                {[...Array(6)].map((_, i) => (
                    <SettingSkeleton2 key={i} />
                ))}

            </div>
        </SkeletonTheme>
    );
};

export default SettingsLoader;
