import Skeleton, { SkeletonTheme } from "react-loading-skeleton";
import 'react-loading-skeleton/dist/skeleton.css';

const ChatItemSkeleton = () => (
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

const ChatSideLoader = () => {
    return (
        <SkeletonTheme baseColor="#e0e0e0" highlightColor="#f5f5f5">
            <div className="space-y-3 pt-3" aria-busy="true" role="status">

                {/* Header Section */}
                <div className="pl-4 pr-2 flex items-center justify-between">
                    <Skeleton width={80} height={32} />
                    <Skeleton circle width={24} height={24} />
                </div>

                {/* Search or Input Section */}
                <div className="pl-4 pr-2 flex items-center">
                    <Skeleton height={32} width="100%" borderRadius="1rem" />
                </div>

                {/* Buttons or Filter Chips */}
                <div className="pl-4 pb-2 flex items-center gap-3">
                    <Skeleton height={32} width={40} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                    <Skeleton height={32} width={80} borderRadius="1rem" />
                </div>

                {/* Chat Items */}
                {[...Array(4)].map((_, i) => (
                    <ChatItemSkeleton key={i} />
                ))}

            </div>
        </SkeletonTheme>
    );
};

export default ChatSideLoader;
