import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import os from "os";
import { glob } from "glob";
import path from "node:path";
import { fileURLToPath } from "node:url";

const input = Object.fromEntries(
    glob
        .sync(["resources/js/pages/**/*.js", "resources/js/modules/**/*.js"])
        .map((file) => {
            console.log(file);
            return [
                // This remove `resources/js/pages/` as well as the file extension from each file, so e.g.
                // resources/js/pages/nested/foo.js becomes nested/foo
                path.relative(
                    "resources/js/pages",
                    file.slice(0, file.length - path.extname(file).length)
                ),
                getFileURLToPathByOS(file),
            ];
        })
);
function getFileURLToPathByOS(file) {
    if (os.type() === "Windows_NT") {
        return (
            "resources\\js" +
            fileURLToPath(new URL(file, import.meta.url)).split(
                "\\resources\\js"
            )[1]
        );
    }

    return (
        "/resources/js" +
        fileURLToPath(new URL(file, import.meta.url)).split("/resources/js")[1]
    );
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...Object.values(input),
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/landing-style.js",
                "resources/js/landing.js",
                "resources/js/style.js",
            ],
            refresh: true,
        }),
    ],
});
