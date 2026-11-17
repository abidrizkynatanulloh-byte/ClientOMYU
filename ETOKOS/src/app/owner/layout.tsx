import React from "react";
import { OwnerSidebar } from "@/components/layout/OwnerSidebar";

export default function OwnerLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="flex-1 flex flex-col lg:flex-row min-h-[calc(100vh-41px)] bg-[#f8fafc]">
      <OwnerSidebar />
      <main className="flex-1 overflow-y-auto px-4 sm:px-8 py-6">
        <div className="max-w-7xl mx-auto">{children}</div>
      </main>
    </div>
  );
}
