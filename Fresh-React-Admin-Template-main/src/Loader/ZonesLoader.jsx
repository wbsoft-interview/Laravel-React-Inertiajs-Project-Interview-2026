import Skeleton, { SkeletonTheme } from "react-loading-skeleton";
import 'react-loading-skeleton/dist/skeleton.css';

const ZonesSkeleton = () => (
    <div className="pl-4 pr-2 flex items-center justify-between gap-3">
        <Skeleton circle height={64} width={64} />
        <div className="space-y-2 w-full">
            <div className="flex items-center justify-between">
                <Skeleton width={84} height={36} />
            </div>
        </div>
    </div>
);

const ZonesLoader = () => {
    return (
        <SkeletonTheme baseColor="#e0e0e0" highlightColor="#f5f5f5">

                {/* Buttons or Filter Chips */}
                {/* <div className="pl-4 pb-2">
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                </div> */}

                {/* Chat Items */}
                {[...Array(4)].map((_, i) => (
                    <ZonesSkeleton key={i} />
                ))}
        </SkeletonTheme>
    );
};

export default ZonesLoader;
