import type { Metadata } from "next";
import { Inter_Tight } from "next/font/google";
import "./globals.css";

const interTight = Inter_Tight({
  subsets: ["latin"],
  variable: "--font-inter-tight",
  weight: ["400", "500", "600", "800", "900"],
});

export const metadata: Metadata = {
  title: "ArusKas — Kelola Keuangan Bisnis Tanpa Ribet",
  description: "Platform manajemen keuangan dan POS terintegrasi untuk UMKM modern. Pencatatan otomatis, laporan real-time, dan manajemen stok dalam satu genggaman.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id" className="scroll-smooth">
      <body
        className={`${interTight.variable} font-sans min-h-screen bg-background text-foreground`}
      >
        {children}
      </body>
    </html>
  );
}
