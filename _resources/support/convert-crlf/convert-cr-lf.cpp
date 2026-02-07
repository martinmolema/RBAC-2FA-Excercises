/*
** Install G++ via  https://www.msys2.org/ or use WSL (apt install g++)
**
** Compile with g++ -std=c++17 convert-cr-lf.cpp
*/

#include <iostream>
#include <filesystem>
#include <fstream>
#include <vector>
#include <string>
#include <unordered_map>

namespace fs = std::filesystem;

void convertLineEndings(const fs::path& filePath) {
    std::ifstream inputFile(filePath, std::ios::binary);
    if (!inputFile.is_open()) {
        std::cerr << "Could not open file: " << filePath << std::endl;
        return;
    }

    std::string content((std::istreambuf_iterator<char>(inputFile)), std::istreambuf_iterator<char>());
    inputFile.close();

    // Replace CRLF with LF
    size_t pos = 0;
    while ((pos = content.find("\r\n", pos)) != std::string::npos) {
        content.replace(pos, 2, "\n");
        pos += 1;
    }

    std::ofstream outputFile(filePath, std::ios::binary);
    if (!outputFile.is_open()) {
        std::cerr << "Could not open file for writing: " << filePath << std::endl;
        return;
    }

    outputFile.write(content.c_str(), content.size());
    outputFile.close();
}

void processDirectory(const fs::path& directory, const std::vector<std::string>& extensions) {
    for (const auto& entry : fs::directory_iterator(directory)) {
        if (entry.is_regular_file()) {
            for (const auto& ext : extensions) {
                if (entry.path().extension() == ext) {
                    convertLineEndings(entry.path());
                }
            }
        }
    }
}

int main() {
    std::unordered_map<std::string, std::vector<std::string>> directories = {
        {"identity-server/ldif", {".ldif"}},
        {"identity-server/ldif-base", {".base"}},
        {"identity-server", {".py","*.sh"}},
        {"dbserver", {"*.sql"}},
        {"webserver/conf", {"*.conf"}},
        {"webserver/ini", {"*.ini"}},
    };

    std::cout << "Starting conversion." << std::endl;
    for (const auto& [dir, exts] : directories) {
        std::cout << dir << std::endl;
        processDirectory(dir, exts);
    }

    std::cout << "Conversion complete." << std::endl;
    return 0;
}
