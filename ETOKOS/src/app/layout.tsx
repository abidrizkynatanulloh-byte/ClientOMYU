import type { Metadata } from "next";
import "./globals.css";
import { KosProvider } from "@/lib/store";
import { DemoTopBar } from "@/components/demo/DemoTopBar";

export const metadata: Metadata = {
  title: "etoKos — Sistem Manajemen Kos-kosan Modern Indonesia",
  description:
    "Aplikasi SaaS property management untuk pemilik kos dan penghuni di Indonesia. Lengkap dengan listing, tagihan otomatis, tiket keluhan, dan akuntansi.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id">
      <body className="antialiased min-h-screen flex flex-col bg-[#faf9f6] text-slate-800">
        <KosProvider>
          <DemoTopBar />
          <div className="flex-1 flex flex-col">{children}</div>
        </KosProvider>
      </body>
    </html>
  );
}
